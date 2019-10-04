<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\SynchronousDispatching;

use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;

final class SynchronousConsumerStub implements Consumer
{
    public $handled = [];

    public function handle(Message $message): void
    {
        $this->handled[] = $message;
    }
}
