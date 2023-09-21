<?php


interface MessageBuilderInterface
{
    public function build(): array;
}

class MessageBuilder implements MessageBuilderInterface
{
    private string $template;
    private int $parcelId;
    private string $parcelName;
    private string $parcelDeliveryDate;


    public function __construct(
        string $template,
        int $parcelId,
        string $parcelName,
        string $parcelDeliveryDate
    ) {
        $this->template = $template;
        $this->parcelId = $parcelId;
        $this->parcelName = $parcelName;
        $this->parcelDeliveryDate = $parcelDeliveryDate;
    }

    public function build(): array
    {
        return [
            'template' => $this->template,
            'parcelId' => $this->parcelId,
            'parcelName' => $this->parcelName,
            'parcelDeliveryDate' => $this->parcelDeliveryDate,
        ];
    }
}


/**
 * Класс для отправки сообщения в очередь.
 */
class Producer
{

    public function send(string $topic, int $key, array $body): array
    {
        // Логика отправки
    }
}


/**
 * Класс для отправки сообщения о статусе посылки.
 */
class OrderMessage extends Producer
{
    private MessageBuilderInterface $messageBuilder;

    public function __construct(MessageBuilderInterface $messageBuilder)
    {
        $this->messageBuilder = $messageBuilder;
    }

    public function sendMessage(string $topic, string $template, int $parcelId, string $parcelName, string $parcelDeliveryDate): array
    {
        // Получить тело сообщения
        $body = $this->messageBuilder->build($template, $parcelId, $parcelName, $parcelDeliveryDate);

        // Отправить сообщение
        return parent::send($topic, $parcelId, $body);
    }
}