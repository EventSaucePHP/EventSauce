<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\Translators;

use EventSauce\EventSourcing\Message;

interface MessageTranslator
{
    public function translateMessage(Message $message): Message;
}
