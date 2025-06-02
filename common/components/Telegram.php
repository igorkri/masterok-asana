<?php
namespace common\components;

use yii\base\Component;
use yii\httpclient\Client;

class Telegram extends Component
{
    public $botToken;
    public $chatId;

    /**
     * Sends a message to the specified Telegram chat.
     *
     * @param string $text The message text to send.
     * @return array|bool The response from the Telegram API or false on failure.
     *
     * Yii::$app->telegram->sendMessage("⚠️ Новий звіт надсилається окремо файлом...");
     *
     * | Тег                                | Опис                                       |
     * | ---------------------------------- | ------------------------------------------ |
     * | `<b>`                              | Жирний текст                               |
     * | `<strong>`                         | Те саме, що `<b>`                          |
     * | `<i>`                              | Курсив                                     |
     * | `<em>`                             | Те саме, що `<i>`                          |
     * | `<u>`                              | Підкреслений текст                         |
     * | `<s>`                              | Закреслений текст                          |
     * | `<strike>`                         | Те саме, що `<s>`                          |
     * | `<del>`                            | Те саме, що `<s>`                          |
     * | `<span class="tg-spoiler">`        | Спойлер (текст буде приховано)             |
     * | `<a href="URL">`                   | Гіперпосилання (тільки з http/https/tg://) |
     * | `<code>`                           | Моноширинний текст (inline)                |
     * | `<pre>`                            | Блок моноширинного тексту                  |
     * | `<pre><code class="language-xxx">` | Syntax highlighting (лише деякі мови)      |
     *
     * ❌ Заборонено:
     * <br>, <p>, <div>, <img>, <table>, інші структурні елементи.
     *
     * Не підтримується вкладеність (<b><i>text</i></b> = ❌).
 */
    public function sendMessage($text)
    {
        $client = new Client(['baseUrl' => "https://api.telegram.org/bot{$this->botToken}"]);
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('sendMessage')
            ->setData([
                'chat_id' => $this->chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ])
            ->send();

        return $response->isOk ? $response->data : false;
    }

    /**
     * Yii::$app->telegram->sendDocument($filePath, "📎 Додано файл з деталями");
     * @param $filePath // Path to the file to be sent
     * @param $caption // Caption for the document
     * @return mixed
     */
    public function sendDocument($filePath, $caption = '')
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendDocument";

        $postFields = [
            'chat_id' => $this->chatId,
            'caption' => $caption,
            'parse_mode' => 'HTML',
            'document' => new \CURLFile($filePath)
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $output = curl_exec($ch);

        curl_close($ch);
        return json_decode($output, true);
    }

}
