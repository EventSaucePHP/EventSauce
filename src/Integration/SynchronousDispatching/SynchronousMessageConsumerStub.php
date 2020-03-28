<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\SynchronousDispatching;

use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\Message;

final class SynchronousMessageConsumerStub implements MessageConsumer
{
    public $handled = [];

    public function handle(Message $message): void
    {
        $this->handled[] = $message;
    }
}
