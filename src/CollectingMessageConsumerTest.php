<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPStan\Testing\TestCase;

class CollectingMessageConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function collecting_messages(): void
    {
        $dispatcher = new CollectingMessageConsumer();

        $dispatcher->handle(new Message(new EventStub('what')));
        $dispatcher->handle(new Message(new EventStub('is')));
        $dispatcher->handle(new Message(new EventStub('up')));

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
        $consumer = new CollectingMessageConsumer();

        $consumer->handle(new Message(new EventStub('what')));
        $consumer->handle(new Message(new EventStub('is')));
        $consumer->handle(new Message(new EventStub('up')));

        $this->assertEquals(
            [
                new EventStub('what'),
                new EventStub('is'),
                new EventStub('up'),
            ],
            $consumer->collectedPayloads(),
        );
    }
}
