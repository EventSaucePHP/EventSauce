<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\EventConsumption;

use EventSauce\EventSourcing\EventConsumer;
use EventSauce\EventSourcing\Message;

/**
 * @testAsset
 */
class EventConsumerStub extends EventConsumer
{
    public string $message = '';
    public object | null $messageObject = null;

    protected function handleDummyEventForConsuming(DummyEventForConsuming $event, Message $message): void
    {
        $this->message = $event->message();
        $this->messageObject = $message;
    }
}
