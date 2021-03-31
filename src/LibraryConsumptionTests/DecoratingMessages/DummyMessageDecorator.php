<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\DecoratingMessages;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

/**
 * @testAsset
 */
class DummyMessageDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        return $message->withHeader('dummy', 'value');
    }
}
