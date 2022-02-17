<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\AntiCorruptionLayer\Translators\MessageTranslator;
use EventSauce\EventSourcing\Message;

/**
 * @testAsset
 * @codeCoverageIgnore
 */
class StubTranslatePrivateToPublic implements MessageTranslator
{
    public function translateMessage(Message $message): Message
    {
        $event = $message->event();

        if ($event instanceof StubPrivateEvent) {
            return new Message(new StubPublicEvent($event->value()));
        }

        return $message;
    }
}
