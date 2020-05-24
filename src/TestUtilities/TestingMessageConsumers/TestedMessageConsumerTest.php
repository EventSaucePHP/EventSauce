<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingMessageConsumers;

use DomainException;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\TestUtilities\MessageConsumerTestCase;
use LogicException;

class TestedMessageConsumerTest extends MessageConsumerTestCase
{
    public function messageConsumer(): MessageConsumer
    {
        return new TestedMessageConsumer(5);
    }

    /**
     * @test
     */
    public function not_expecting_an_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->when(
            new ConsumerEvent(),
            new ConsumerEvent(),
            new ConsumerEvent(),
            new ConsumerEvent(),
            new ConsumerEvent()
        );
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function expecting_an_exception(): void
    {
        $this->when(
            new ConsumerEvent(),
            new ConsumerEvent(),
            new ConsumerEvent(),
            new ConsumerEvent(),
            new ConsumerEvent()
        );
        $this->expectToFail(new LogicException('Too many messages'));
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function expecting_an_incorrect_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->given(
            new ConsumerEvent(),
            new ConsumerEvent(),
            new ConsumerEvent()
        )->when(
            new ConsumerEvent(),
            new ConsumerEvent()
        );
        $this->expectToFail(new DomainException('Wrong exception'));
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function expecting_assertions(): void
    {
        $this->when(new Message(new ConsumerEvent()), new ConsumerEvent())
            ->then(function (TestedMessageConsumer $consumer): void {
                $this->assertEquals(2, $consumer->numberOfMessagesProcessed());
            });
    }
}
