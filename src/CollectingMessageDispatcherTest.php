<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;

class CollectingMessageDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function collecting_messages(): void
    {
        $dispatcher = new CollectingMessageDispatcher();

        $dispatcher->dispatch(
            new Message(new EventStub('what')),
            new Message(new EventStub('is')),
            new Message(new EventStub('up')),
        );

        $this->assertEquals(
            [
                new Message(new EventStub('what')),
                new Message(new EventStub('is')),
                new Message(new EventStub('up')),
            ],
            $dispatcher->collectedMessages(),
        );
    }

    /**
     * @test
     */
    public function exposing_collected_payloads(): void
    {
        $dispatcher = new CollectingMessageDispatcher();

        $dispatcher->dispatch(
            new Message(new EventStub('what')),
            new Message(new EventStub('is')),
            new Message(new EventStub('up')),
        );

        $this->assertEquals(
            [
                new EventStub('what'),
                new EventStub('is'),
                new EventStub('up'),
            ],
            $dispatcher->collectedPayloads(),
        );
    }
}
