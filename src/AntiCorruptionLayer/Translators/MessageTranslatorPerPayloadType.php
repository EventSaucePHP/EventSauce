<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\Translators;

use EventSauce\EventSourcing\Message;
use function get_class;

class MessageTranslatorPerPayloadType implements MessageTranslator
{
    /**
     * @param array<class-string, MessageTranslator> $translators
     */
    public function __construct(private array $translators)
    {
    }

    public function translateMessage(Message $message): Message
    {
        $type = get_class($message->event());
        $translator = $this->translators[$type] ?? null;

        if ($translator === null) {
            return $message;
        }

        return $translator->translateMessage($message);
    }
}
