<?php

namespace EventSauce\EventSourcing\Integration\DecoratingMessages;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class DummyMessageDecorator implements MessageDecorator
{
    public function decorate(Message $message): Message
    {
        return $message->withMetadata('dummy', 'value');
    }
}