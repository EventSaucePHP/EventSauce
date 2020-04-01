<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\AggregateRootTestCase;
use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PointInTime;
use EventSauce\EventSourcing\Time\Clock;
use LogicException;

class ExampleAggregateRootTest extends AggregateRootTestCase
{
    protected function aggregateRootClassName(): string
    {
        return DummyAggregate::class;
    }

    protected function commandHandler(AggregateRootRepository $repository, Clock $clock)
    {
        return new DummyCommandHandler($repository, $clock);
    }

    /**
     * @test
     */
    public function test_static_initiator(): void
    {
        $this->when(new InitiatorCommand($this->aggregateRootId()));
        $this->then(new AggregateWasInitiated());
    }

    /**
     * @test
     */
    public function executing_a_command_successfully(): void
    {
        $aggregateRootId = $this->aggregateRootId();
        $this->when(new DummyCommand($aggregateRootId));
        $this->then(new DummyTaskWasExecuted());
    }

    /**
     * @test
     */
    public function there_is_a_clock(): void
    {
        $this->assertInstanceOf(PointInTime::class, $this->pointInTime());
    }

    /**
     * @test
     */
    public function asserting_nothing_happened(): void
    {
        $aggregateRootId = $this->aggregateRootId();
        $this->when(new IgnoredCommand($aggregateRootId));
        $this->thenNothingShouldHaveHappened();
    }

    /**
     * @test
     */
    public function expecting_exceptions(): void
    {
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()))
            ->expectToFail(new DummyException());
    }

    /**
     * @test
     */
    public function not_expecting_exceptions(): void
    {
        $this->expectException(DummyException::class);
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()));
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function expecting_the_wrong_exception(): void
    {
        $this->expectException(DummyException::class);
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()))
            ->expectToFail(new LogicException());
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function setting_preconditions(): void
    {
        $id = $this->aggregateRootId();
        $this->given(new DummyIncrementingHappened(1))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened(2));
    }

    /**
     * @test
     */
    public function setting_preconditions_from_other_aggregates(): void
    {
        $id = $this->aggregateRootId();
        $this->on(DummyAggregateRootId::generate())
            ->stage(new DummyIncrementingHappened(10))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened(1));
    }

    /**
     * @test
     */
    public function messages_have_a_sequence(): void
    {
        $id = $this->aggregateRootId();
        $this->given(new DummyIncrementingHappened(10))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened(11));

        /** @var Message[] $messages */
        $messages = [];

        /** @var Message $message */
        foreach ($this->messageRepository->retrieveAll($id) as $message) {
            $messages[] = $message;
        }

        $this->assertContainsOnlyInstancesOf(Message::class, $messages);
        $this->assertEquals(1, $messages[0]->header(Header::AGGREGATE_ROOT_VERSION));
        $this->assertEquals(2, $messages[1]->header(Header::AGGREGATE_ROOT_VERSION));
    }

    protected function handle($command): void
    {
        $commandHandler = $this->commandHandler($this->repository, $this->clock());
        $commandHandler->handle($command);
    }

    protected function newAggregateRootId(): AggregateRootId
    {
        return DummyAggregateRootId::generate();
    }
}
