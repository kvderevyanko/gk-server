<?php


namespace app\components;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class EspRequest extends Widget
{
    /**
     * Отправка запроса на esp
     * @param string $url
     * @param array $request
     * @return string
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function send($url, array $request)
    {
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData($request)
            ->setOptions([
                'timeout' => 2, // set timeout to 5 seconds for the case server is not responding
            ])
            ->send();
        if ($response->isOk) {
            return $response->content;
        }
        return 'error';
    }
}