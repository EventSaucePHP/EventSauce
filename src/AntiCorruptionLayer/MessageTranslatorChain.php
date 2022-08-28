<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;

class MessageTranslatorChain implements MessageTranslator
{
    /** @var MessageTranslator[] */
    private array $translators;

    public function __construct(MessageTranslator ...$translators)
    {
        $this->translators = $translators;
    }

    public function translateMessage(Message $message): Message
    {
        foreach ($this->translators as $translator) {
            $message = $translator->translateMessage($message);
        }

        return $message;
    }
}
