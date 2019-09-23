<?php

namespace EventSauce\EventSourcing\Integration\EventConsumption;

use EventSauce\EventSourcing\EventConsumer;

class EventConsumerStub extends EventConsumer
{
    public $message = '';

    protected function handleDummyEventForConsuming(DummyEventForConsuming $event)
    {
        $this->message = $event->message();
    }
}
