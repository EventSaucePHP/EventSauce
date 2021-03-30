<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\SynchronousDispatching;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDispatcherChain;
use EventSauce\EventSourcing\PayloadStub;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use PHPUnit\Framework\TestCase;

class SynchronousMessageDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function dispatching_messages_synchronously(): void
    {
        $stubConsumer = new SynchronousMessageConsumerStub();
        $syncDispatcher = new SynchronousMessageDispatcher($stubConsumer, $stubConsumer);
        $message = new Message(new PayloadStub('value'));
        $syncDispatcher->dispatch($message, $message);
        $this->assertEquals([$message, $message, $message, $message], $stubConsumer->handled);
    }

    /**
     * @test
     */
    public function dispatching_using_a_chain(): void
    {
        $stubconsumer = new SynchronousMessageConsumerStub();
        $syncDispatcher = new SynchronousMessageDispatcher($stubconsumer);
        $dispatcherChain = new MessageDispatcherChain($syncDispatcher, $syncDispatcher);
        $message = new Message(new PayloadStub('value'));
        $dispatcherChain->dispatch($message);
        $this->assertEquals([$message, $message], $stubconsumer->handled);
    }
}
