<?php

namespace EventSauce\EventSourcing\Integration\SynchronousDispatching;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use PHPUnit\Framework\TestCase;

class SynchronousMessageDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function dispatching_messages_synchronously()
    {
        $stubconsumer = new SynchronousConsumerStub();
        $syncDispatcher = new SynchronousMessageDispatcher($stubconsumer, $stubconsumer);
        $message = new Message(new SynchronousEventStub());
        $syncDispatcher->dispatch($message, $message);

        $this->assertEquals([$message, $message, $message, $message], $stubconsumer->handled);
    }
}