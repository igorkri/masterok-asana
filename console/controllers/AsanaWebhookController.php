<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use Asana\Client;

class AsanaWebhookController extends Controller
{
    public static $TOKEN;

    public function init()
    {
        parent::init();
        self::$TOKEN = Yii::$app->params['tokenAsana'];
    }
    const WORKSPACE_GID = '1202666709283080'; // GID рабочей области Asana
    const TARGET_URL = 'https://yourdomain.com/webhook-handler'; // URL вашего сайта для получения вебхуков

    /**
     * Инициализация клиента Asana
     */
    private function getClient()
    {
        return Client::accessToken(self::$TOKEN);
    }

    /**
     * Создание вебхука для отслеживания всех задач
     */
    public function actionCreateWebhook()
    {
        $client = $this->getClient();

        try {
            $webhook = $client->webhooks->create([
                'resource' => self::WORKSPACE_GID,
                'target' => self::TARGET_URL,
            ]);

            print_r($webhook); // Вывод информации о созданном вебхуке
        } catch (\Asana\Errors\InvalidRequestError $e) {
            echo "Error: " . $e->getMessage();
            print_r($e->response->raw_body);
        }
    }

    /**
     * Обработчик вебхуков от Asana
     */
    public function actionWebhookHandler()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isGet && Yii::$app->request->headers->has('X-Hook-Secret')) {
            // Подтверждение вебхука
            Yii::$app->response->headers->set('X-Hook-Secret', Yii::$app->request->headers->get('X-Hook-Secret'));
            Yii::$app->response->statusCode = 200;
            return "Webhook confirmed";
        } elseif (Yii::$app->request->isPost) {
            $data = Yii::$app->request->rawBody;
            $events = json_decode($data, true);

            // Обработка событий
            if (isset($events['events'])) {
                foreach ($events['events'] as $event) {
                    if (in_array($event['action'], ['added', 'changed']) && $event['resource']['resource_type'] == 'task') {
                        // Отправка данных на ваш сайт
                        $this->sendToSite($event);
                    }
                }
            }

            return ['status' => 'success'];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'Invalid request'];
        }
    }

    /**
     * Отправка данных на ваш сайт
     */
    private function sendToSite($event)
    {
        $url = 'https://yourdomain.com/api/receive-task'; // URL вашего API для получения данных
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        Yii::info("Sent to site: " . print_r($event, true), 'asana-webhook');
    }
}
