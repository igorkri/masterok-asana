<?php

namespace backend\controllers;



use backend\models\content\FormDescription;
use backend\models\content\FormTitle;
use common\models\page\DecorationImage;
use common\models\shop\ImageProduct;
use common\models\shop\Product;
use common\models\Translation;
use modules\seoblock\models\ContentBlock;
use modules\seoblock\models\PageContent;
use Yii;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\Response;

class AjaxController extends Controller
{
    // Ваши остальные действия

    public function actionGenerateSlug()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $name = Yii::$app->request->post('name');
        $slug = Inflector::slug($name);

        return ['slug' => $slug];
    }

    public function actionSorting($modelName)
    {
//        Yii::warning($modelName);
//        Yii::warning($modelName::find()->asArray()->all());
        if (!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            return false;
        }
        $data = Yii::$app->request->post('sorting');
        foreach ($data as $key => $value) {
//            \Yii::$app->cache->flush();
            $model = $modelName::findOne(['id' => $value]);
            $model->sort = $key;
            if(!$model->save(false)) {
                Yii::error($model->errors);
            }
        }
    }


    public function actionProductImageDelete($product_id, $id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $image = ImageProduct::find()->where(['id' => $id])->andWhere(['product_id' => $product_id])->one();
        if($image->delete()) {
            Yii::$app->session->setFlash('success', "Успішно видалено");
            return $this->redirect(['/shop/product/images',
                'product_id' => $product_id
            ]);
        }
        Yii::$app->session->setFlash('warning', "Не видалено");
        return $this->redirect(['/shop/product/images',
            'product_id' => $product_id
        ]);
    }

    public function actionDecorationImageDelete($decoration_id, $id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $image = DecorationImage::find()->where(['id' => $id])->andWhere(['decoration_id' => $decoration_id])->one();
        if($image->delete()) {
            Yii::$app->getSession()->addFlash('success', "Успішно видалено");
            return $this->redirect(['/page/decoration/image',
                'decoration_id' => $decoration_id
            ]);
        }

        Yii::$app->getSession()->addFlash('warning', "Не видалено");
        return $this->redirect(['/page/decoration/image',
            'decoration_id' => $decoration_id
        ]);
    }

    public function actionAddContent(string $typeBlock)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        if($request->isPost && $typeBlock != ContentBlock::TYPE_PRODUCTS) {
            $res = ContentBlock::_save($request->post(), $typeBlock);
            return ['success' => $res];
        }

        switch ($typeBlock) {
            case ContentBlock::TYPE_PRODUCTS:
                $category_id = $request->get('category_id');
                $type = $request->get('type');
                $classModel = $request->get('classModel');
                $product_ids = Product::find()
                    ->where(['category_id' => $category_id])
                    ->andWhere(['visible' => 1])
                    ->andWhere(['status' => 'is_stock'])
                    ->limit(6)->column();
                $data = [
                    'type' => $type,
                    'pageId' => $category_id,
                    'classModel' => $classModel,
                    'FormProducts' => [
                        'product_ids' => $product_ids
                    ]
                ];
                $res = ContentBlock::_saveProducts($data, $typeBlock);
                return ['success' => $res];
                break;
            case ContentBlock::TYPE_TEXT:
                $model = new FormDescription();
                return $this->renderAjax('content/_description_form', [
                    'model' => $model,
                    'translations' => Translation::getLanguageKeys(),
                    'typeBlock' => $typeBlock
                ]);
                break;
            case ContentBlock::TYPE_TITLE:
                $model = new FormTitle();
                return $this->renderAjax('content/_title_form', [
                    'model' => $model,
                    'translations' => Translation::getLanguageKeys(),
                    'typeBlock' => $typeBlock
                ]);
                break;
            default:
                return ['error' => 'Не вірний тип'];
        }

    }


    /**
     * @return array
     */
    public function actionUpdateOrderPageContent()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('sort');
            foreach ($data as $key => $value) {
                $model = PageContent::findOne(['id' => $value]);
                $model->sort = $key;
                $model->save(false);
            }
            return ['success' => true];

        }

        return ['success' => false];
    }

    public function actionToggleProduct()
    {
        $session = Yii::$app->session;

        $id = Yii::$app->request->post('id'); // ID продукта
        $action = Yii::$app->request->post('action'); // 'add' или 'remove'

        $product = Product::findOne($id);
        if (!$product) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => 'Product not found'];
        }

        $category_id = $product->category_id;

        // Инициализация общего массива для всех категорий
        $categoriesData = $session->has('categoriesData') ? $session->get('categoriesData') : [];

        // Инициализация массива для текущей категории
        $data = isset($categoriesData[$category_id]) ? $categoriesData[$category_id] : [];

        if ($action === 'add') {
            if (!in_array($id, $data)) {
                $data[] = $id; // Добавляем продукт
            }
        } elseif ($action === 'remove') {
            $key = array_search($id, $data);
            if ($key !== false) {
                unset($data[$key]); // Удаляем продукт
            }
        }

        // Обновляем данные для категории
        $categoriesData[$category_id] = array_values($data);

        // Сохраняем общий массив в сессии
        $session->set('categoriesData', $categoriesData);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true, 'data' => $categoriesData];
    }

    public function actionSearchProducts($q, $category_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
        $products = Product::find()
            ->joinWith(['translation t']) // Указываем алиас `t` для таблицы `product_translation`
            ->where(['like', 't.name', $q]) // Используем алиас
            ->orWhere(['like', 'sku', $q]) // Поиск по SKU в основной таблице
            ->andWhere(['category_id' => $category_id])
            ->all();


        if (empty($products)) {
            return '<div class="alert alert-warning">Ничего не найдено.</div>';
        }

        $html = '';
        foreach ($products as $product) {
            $html .= $this->renderPartial('content/_product_item', ['product' => $product]);
        }

        return $html;
    }

    public function actionDeleteBlock()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $contentBlockId = Yii::$app->request->post('content_block_id');
        if ($contentBlockId) {
            try {
                // Логика удаления блока с переданным content_block_id
                // Например:
                ContentBlock::deleteAll(['id' => $contentBlockId]);
                return ['success' => true];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

        return ['success' => false, 'message' => 'ID блока не передан.'];
    }


    /**
     * Загрузка изображения для редактора
     *
     *
     */
    public function actionUploadImageEditor()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $file = $_FILES['file'] ?? null;
        if (!$file) {
            Yii::error('Файл не загружен.');
            return ['success' => false, 'message' => 'Файл не загружен.'];
        }

        $path = Yii::getAlias('@frontend/web/uploads/editor/');
        if (!is_writable($path)) {
            Yii::error("Нет прав на запись в: " . $path);
            return ['success' => false, 'message' => 'Нет прав на запись.'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // Разрешенные форматы

        if (!in_array($extension, $allowedExtensions)) {
            Yii::error("Недопустимый формат файла: " . $extension);
            return ['success' => false, 'message' => 'Недопустимый формат файла.'];
        }

        $fileName = uniqid() . '.' . $extension;
        $filePath = $path . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            Yii::error("Ошибка загрузки файла: " . $filePath);
            return ['success' => false, 'message' => 'Ошибка при сохранении файла.'];
        }

        // Если загружаемый файл уже WebP, просто возвращаем URL без конвертации
        if ($extension === 'webp') {
            return '/uploads/editor/' . $fileName;
        }

        // Конвертируем в WebP
        $webpFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
        $webpFilePath = $path . $webpFileName;

        try {
            if (extension_loaded('imagick')) {
                $imagick = new \Imagick();
                $formats = $imagick->queryFormats("WEBP");
                if (!empty($formats)) {
                    $imagick->readImage($filePath);
                    $desiredWidth = 1200;
                    $originalWidth = $imagick->getImageWidth();
                    $originalHeight = $imagick->getImageHeight();

                    if ($originalWidth > $desiredWidth) {
                        $newHeight = ($desiredWidth / $originalWidth) * $originalHeight;
                        $imagick->resizeImage($desiredWidth, $newHeight, \Imagick::FILTER_LANCZOS, 1);
                    }

                    $imagick->setImageFormat('webp');
                    $imagick->setImageCompressionQuality(80);
                    $imagick->writeImage($webpFilePath);
                    $imagick->clear();
                    $imagick->destroy();
                } else {
                    throw new \Exception("Imagick не поддерживает WebP.");
                }
            } elseif (function_exists('imagewebp')) {
                $image = imagecreatefromstring(file_get_contents($filePath));
                if ($image) {
                    imagewebp($image, $webpFilePath, 80);
                    imagedestroy($image);
                } else {
                    throw new \Exception("Ошибка обработки изображения через GD.");
                }
            } else {
                throw new \Exception("На сервере нет поддержки WebP (ни Imagick, ни GD).");
            }

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return '/uploads/editor/' . $webpFileName;
        } catch (\Exception $e) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            Yii::error('Ошибка при конвертации в WebP: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Ошибка при конвертации в WebP: ' . $e->getMessage()];
        }
    }



//    public function actionUploadImageEditor()
//    {
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//
//        // Получаем файл из запроса
//        $file = $_FILES['file'];
//
//
//        if (!$file) {
//            Yii::error('Файл не загружен.' . print_r($_FILES, true));
//            return ['success' => false, 'message' => 'Файл не загружен.'];
//        }
//
//        $path = Yii::getAlias('@frontend/web/uploads/editor/');
//        $fileName = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
//        $filePath = $path . $fileName;
//
//        // Сохраняем оригинальное изображение
//        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
//            Yii::error('Ошибка при сохранении файла.' . $filePath);
//            return ['success' => false, 'message' => 'Ошибка при сохранении файла.'];
//        }
//
//        // Конвертируем в WebP
//        $webpFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
//        $webpFilePath = $path . $webpFileName;
//
//        try {
//            $imagick = new \Imagick($filePath);
//
//            // Устанавливаем желаемую ширину изображения
//            $desiredWidth = 1200; // Укажите нужную ширину в пикселях
//            $originalWidth = $imagick->getImageWidth();
//            $originalHeight = $imagick->getImageHeight();
//
//            // Сохраняем пропорции, если оригинальное изображение шире указанной ширины
//            if ($originalWidth > $desiredWidth) {
//                $newHeight = ($desiredWidth / $originalWidth) * $originalHeight;
//                $imagick->resizeImage($desiredWidth, $newHeight, \Imagick::FILTER_LANCZOS, 1);
//            }
//
//            $imagick->setImageFormat('webp');
//            $imagick->setImageCompressionQuality(80);
//            $imagick->writeImage($webpFilePath);
//            $imagick->clear();
//            $imagick->destroy();
//
//            // Удаляем оригинальное изображение
//            if (file_exists($filePath)) {
//                unlink($filePath);
//            }
//
//            return '/uploads/editor/' . $webpFileName;
//        } catch (\Exception $e) {
//            // Удаляем оригинальный файл, если произошла ошибка конвертации
//            if (file_exists($filePath)) {
//                unlink($filePath);
//            }
//            Yii::error('Ошибка при конвертации в WebP: ' . $e->getMessage());
//            return ['success' => false, 'message' => 'Ошибка при конвертации в WebP: ' . $e->getMessage()];
//        }
//    }



    /**
     * Уаление изображения для редактора
     *
     */
    public function actionDeleteImageEditor()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Получаем путь к изображению из POST-запроса
        $image = Yii::$app->request->post('file');
        if (!$image) {
            return ['success' => false, 'message' => 'Файл не указан.'];
        }

        // Удаляем префикс URL, чтобы получить относительный путь
        $relativePath = str_replace('/uploads/editor/', '', parse_url($image, PHP_URL_PATH));

        // Полный путь к файлу
        $path = Yii::getAlias('@frontend/web/uploads/editor/') . $relativePath;

        // Проверяем, существует ли файл
        if (!file_exists($path)) {
            return ['success' => false, 'message' => 'Файл не найден: ' . $path];
        }

        // Удаляем файл
        if (unlink($path)) {
            return ['success' => true, 'message' => 'Файл успешно удалён.'];
        } else {
            return ['success' => false, 'message' => 'Не удалось удалить файл: ' . $path];
        }
    }



}
