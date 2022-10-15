<?php
declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAntiCorruptionLayers;

use EventSauce\EventSourcing\AntiCorruptionLayer\MessageTranslator;
use EventSauce\EventSourcing\Message;

class TranslateEventAToEventB implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        $event = $message->payload();

        if ($event instanceof EventA) {
            return new Message(new EventB($event->value));
        }

        return $message;
    }
}