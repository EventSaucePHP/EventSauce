<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;

use function substr;

class MySQL8DateFormatting implements MessageSerializer
{
    public function __construct(
        private MessageSerializer $messageSerializer,
        private string $field = Header::TIME_OF_RECORDING,
    ) {
    }

    public function serializeMessage(Message $message): array
    {
        $payload = $this->messageSerializer->serializeMessage($message);
        $dateTimeString = $payload['headers'][$this->field] ?? null;

        if ($dateTimeString !== null) {
            $payload['headers'][$this->field] = substr($dateTimeString, 0, -2) . ':' . substr($dateTimeString, -2);
        }

        return $payload;
    }

    public function unserializePayload(array $payload): Message
    {
        $dateTimeString = $payload['headers'][$this->field] ?? null;

        if ($dateTimeString !== null) {
            $payload['headers'][$this->field] = substr($dateTimeString, 0, -3) . substr($dateTimeString, -2);
        }

        return $this->messageSerializer->unserializePayload($payload);
    }
}
