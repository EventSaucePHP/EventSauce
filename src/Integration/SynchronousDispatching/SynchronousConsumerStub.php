<?php

namespace EventSauce\EventSourcing\Integration\SynchronousDispatching;

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;

final class SynchronousConsumerStub implements Consumer
{
    public $handled = [];

    public function handle(Message $message)
    {
        $this->handled[] = $message;
    }
}