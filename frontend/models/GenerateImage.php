<?php

namespace frontend\models;

use yii\base\Model;

class GenerateImage extends Model
{

    public $queries = [
        'nature' => 'природа',
        'mountains' => 'гори',
        'ocean' => 'океан',
        'forest' => 'ліс',
        'sunset' => 'захід сонця',
        'city' => 'місто',
        'architecture' => 'архітектура',
        'animals' => 'тварини',
        'birds' => 'птахи',
        'cats' => 'коти',
        'dogs' => 'собаки',
        'flowers' => 'квіти',
        'technology' => 'технології',
        'cars' => 'автомобілі',
        'bikes' => 'велосипеди',
        'aeroplanes' => 'літаки',
        'food' => 'їжа',
        'desserts' => 'десерти',
        'drinks' => 'напої',
        'people' => 'люди',
        'portrait' => 'портрет',
        'fashion' => 'мода',
        'sports' => 'спорт',
        'fitness' => 'фітнес',
        'business' => 'бізнес',
        'education' => 'освіта',
        'science' => 'наука',
        'space' => 'космос',
        'galaxy' => 'галактика',
        'stars' => 'зірки',
        'night' => 'ніч',
        'landscape' => 'пейзаж',
        'abstract' => 'абстракція',
        'art' => 'мистецтво',
        'music' => 'музика',
        'festival' => 'фестиваль',
        'history' => 'історія',
        'culture' => 'культура',
        'travel' => 'подорожі',
        'adventure' => 'пригоди'
    ];

    public $formats = ['jpg' => 'jpg', 'png' => 'png', 'webp' => 'webp'];

    public $count;
    public $width;
    public $height;
    public $format;
//    public $query;

    public function rules()
    {
        return [
            [['count', 'width', 'height', 'format'/*,'query'*/], 'required'],
            [['count', 'width', 'height'], 'integer', 'min' => 1],
           // ['query', 'in', 'range' => array_keys($this->queries)],
            ['format', 'in', 'range' => $this->formats]
        ];
    }

    public function attributeLabels()
    {
        return [
            'count' => 'Кількість зображень',
            'width' => 'Ширина',
            'height' => 'Висота',
            'format' => 'Формат',
//            'query' => 'Запит'
        ];
    }

    public function generate()
    {
        $tempDir = \Yii::getAlias('@runtime/images');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $zipName = 'images-' . $this->format . '-' . $this->width. 'x' . $this->height .'.zip';
        $zipFile = $tempDir . $zipName;
        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return ['error' => 'Cannot create ZIP file'];
        }

        for ($i = 0; $i < $this->count; $i++) {
//            $imageUrl = "https://source.unsplash.com/{$this->width}x{$this->height}/?{$this->query}";
//             https://picsum.photos
            $imageUrl = "https://picsum.photos/{$this->width}/{$this->height}?random=$i";
            $imagePath = "$tempDir/image_$i.{$this->format}";
            file_put_contents($imagePath, file_get_contents($imageUrl));
            $zip->addFile($imagePath, "image_$i.{$this->format}");
        }

        $zip->close();

        return \Yii::$app->response->sendFile($zipFile, $zipName)->on(\yii\web\Response::EVENT_AFTER_SEND, function ($event) use ($zipFile, $tempDir) {
            unlink($zipFile);
            array_map('unlink', glob("$tempDir/*.*"));
            rmdir($tempDir);
        });
    }

}