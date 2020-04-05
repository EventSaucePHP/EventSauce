<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\EventHandling;

use EventSauce\EventSourcing\EventHandlingMessageConsumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PayloadStub;

class DummyEventHandler extends EventHandlingMessageConsumer
{
    /**
     * @var PayloadStub
     */
    public $event;

    /**
     * @var Message
     */
    public $message;

    protected function handlePayloadStub(PayloadStub $event, Message $message): void
    {
        $this->event = $event;
        $this->message = $message;
    }
}
