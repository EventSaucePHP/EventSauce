<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\SynchronousDispatching;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

/**
 * @testAsset
 */
final class SynchronousMessageConsumerStub implements MessageConsumer
{
    /** @var list<Message> */
    public array $handled = [];

    public function handle(Message $message): void
    {
        $this->handled[] = $message;
    }
}
