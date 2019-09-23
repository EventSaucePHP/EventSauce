<?php

namespace EventSauce\EventSourcing\Integration\EventConsumption;

use EventSauce\EventSourcing\EventConsumer;
use EventSauce\EventSourcing\Message;

class EventConsumerStub extends EventConsumer
{
    public $message = '';
    public $messageObject = null;

    protected function handleDummyEventForConsuming(DummyEventForConsuming $event, Message $message)
    {
        $this->message = $event->message();
        $this->messageObject = $message;
    }
}
