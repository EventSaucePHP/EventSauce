<?php

namespace EventSauce\EventSourcing;

final class SynchronousConsumerStub implements Consumer
{
    public $handled = [];

    public function handle(Message $message)
    {
        $this->handled[] = $message;
    }
}