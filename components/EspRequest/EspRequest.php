<?php
namespace app\components\EspRequest;

use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class EspRequest implements EspRequestInterface
{

    private string $host;
    private string $file;
    private array $params;

    const RESPONSE_ERROR = 'error';
    const TIMEOUT = 2;

    public function __construct(
        string $host,
        string $file,
        array $params
    )
    {
        $this->host = trim($host);
        $this->file = $file;
        $this->params = $params;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public  function send(): string
    {
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
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