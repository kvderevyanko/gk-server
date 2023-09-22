<?php
namespace app\components\EspRequest;


abstract class EspRequest implements EspRequestInterface
{
    protected string $host;
    protected string $file;
    protected array $params;

    const RESPONSE_ERROR = 'error';
    const TIMEOUT = 2;

    /**
     * @param string $host
     * @param string $file
     * @param array $params
     */
    public function __construct(
        string $host,
        string $file,
        array $params
    ) {
        $this->host = trim($host);
        $this->file = $file;
        $this->params = $params;
    }
}