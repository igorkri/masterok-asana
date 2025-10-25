<?php

namespace backend\components;

use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

/**
 * API Key Auth Filter
 * Проверяет наличие API ключа в заголовке запроса
 */
class ApiKeyAuth extends ActionFilter
{
    /**
     * API ключ для доступа к API
     * В production лучше хранить в params или env
     */
    public $apiKey = null;

    /**
     * Включить/выключить аутентификацию
     */
    public $enabled = false;

    public function beforeAction($action)
    {
        if (!$this->enabled) {
            return true;
        }

        // Получаем API ключ из заголовка
        $apiKey = Yii::$app->request->headers->get('X-API-Key');

        // Если ключ не установлен в конфиге, используем из params
        $validKey = $this->apiKey ?? Yii::$app->params['apiKey'] ?? null;

        // Если аутентификация не настроена, разрешаем доступ
        if ($validKey === null) {
            return true;
        }

        // Проверяем API ключ
        if ($apiKey !== $validKey) {
            throw new UnauthorizedHttpException('Invalid API key');
        }

        return true;
    }
}

