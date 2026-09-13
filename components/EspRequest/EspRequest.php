<?php
namespace app\components\EspRequest;


abstract class EspRequest implements EspRequestInterface
{
    protected string $host;
    protected string $file;
    protected array $params;

    const RESPONSE_ERROR = 'error';
    // An ESP8266 may need a little longer immediately after boot while Lua
    // restores state. Five seconds still bounds unavailable-device requests
    // while avoiding false failures for a valid first command.
    const TIMEOUT = 5;

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
