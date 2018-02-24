<?php

namespace EventSauce\EventSourcing\Integration\SynchronousDispatching;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use EventSauce\EventSourcing\UuidAggregateRootId;
use PHPUnit\Framework\TestCase;

class SynchronousMessageDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function dispatching_messages_synchronously()
    {
        $aggregateRootId = UuidAggregateRootId::create();
        $stubconsumer = new SynchronousConsumerStub();
        $syncDispatcher = new SynchronousMessageDispatcher($stubconsumer, $stubconsumer);
        $message = new Message($aggregateRootId, new SynchronousEventStub());
        $syncDispatcher->dispatch($message, $message);
        $this->assertEquals([$message, $message, $message, $message], $stubconsumer->handled);
    }
}