<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\EventHandling;

use EventSauce\EventSourcing\EventHandlingMessageConsumer;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Message;

class DummyEventHandler extends EventHandlingMessageConsumer
{
    public EventStub $event;
    public Message $message;

    protected function handleEventStub(EventStub $event, Message $message): void
    {
        $this->event = $event;
        $this->message = $message;
    }
}
