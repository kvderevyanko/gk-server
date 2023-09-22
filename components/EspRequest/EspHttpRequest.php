<?php
namespace app\components\EspRequest;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

/**
 * Отправка запроса на микроконтроллер через HTTP
 */
class EspHttpRequest extends EspRequest
{
    /**
     * @return string
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function send(): string
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($this->host.$this->file)
            ->setData($this->params)
            ->setOptions([
                'timeout' => self::TIMEOUT,
            ])
            ->send();
        if ($response->isOk) {
            return $response->content;
        }
        return self::RESPONSE_ERROR;
    }
}