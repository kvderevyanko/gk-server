<?php
namespace app\components\EspRequest;
use yii\httpclient\Exception;

class EspRequestSenderFactory
{
    /**
     * @param string $host
     * @param string $file
     * @param array $params
     * @param string $type
     * @return EspRequest
     * @throws Exception
     */
    public function createEspRequest(string $host, string $file, array $params, string $type = 'curl' ): EspRequest
    {
        switch ($type) {
            case 'curl':
                return new EspCurlRequest($host, $file, $params);
            case 'stream':
                return new EspHttpRequest($host, $file, $params);
            default:
                throw new Exception('Неизвестный тип запроса: ' . $type);
        }
    }
}
