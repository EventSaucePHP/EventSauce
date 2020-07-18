<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\EventHandling;

use EventSauce\EventSourcing\EventHandlingMessageConsumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\EventStub;

class DummyEventHandler extends EventHandlingMessageConsumer
{
    /**
     * @var EventStub
     */
    public $event;

    /**
     * @var Message
     */
    public $message;

    protected function handleEventStub(EventStub $event, Message $message): void
    {
        $this->event = $event;
        $this->message = $message;
    }
}
