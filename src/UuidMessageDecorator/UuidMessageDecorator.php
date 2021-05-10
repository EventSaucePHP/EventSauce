<?php

namespace EventSauce\EventSourcing\UuidMessageDecorator;

use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;

class UuidMessageDecorator implements MessageDecorator
{
    private UuidGenerator $generator;

    public function __construct(UuidGenerator $generator = null)
    {
        $this->generator = $generator ?? new UuidV4Generator();
    }

    public function decorate(Message $message): Message
    {
        return $message->withHeader(Header::EVENT_ID, $this->generator->generate());
    }
}
