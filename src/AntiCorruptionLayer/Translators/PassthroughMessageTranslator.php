<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\Translators;

use EventSauce\EventSourcing\Message;

class PassthroughMessageTranslator implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        return $message;
    }
}
