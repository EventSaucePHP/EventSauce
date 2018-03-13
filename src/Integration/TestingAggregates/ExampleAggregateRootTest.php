<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\AggregateRootTestCase;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\UuidAggregateRootId;
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
    public function executing_a_command_sucessfully()
    {
        $aggregateRootId = $this->aggregateRootId();
        $this->when(new DummyCommand($aggregateRootId));
        $this->then(new DummyTaskWasExecuted($this->pointInTime()));
    }

    /**
     * @test
     */
    public function asserting_nothing_happened()
    {
        $aggregateRootId = $this->aggregateRootId();
        $this->when(new IgnoredCommand($aggregateRootId));
        $this->thenNothingShouldHaveHappened();
    }

    /**
     * @test
     */
    public function expecting_exceptions()
    {
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()))
            ->expectToFail(new DummyException());
    }

    /**
     * @test
     */
    public function not_expecting_exceptions()
    {
        $this->expectException(DummyException::class);
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()));
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function expecting_the_wrong_exception()
    {
        $this->expectException(DummyException::class);
        $this->when(new ExceptionInducingCommand($this->aggregateRootId()))
            ->expectToFail(new LogicException());
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function setting_preconditions()
    {
        $id = $this->aggregateRootId();
        $this->given(new DummyIncrementingHappened($this->pointInTime(), 1))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened($this->pointInTime(), 2));
    }

    /**
     * @test
     */
    public function setting_preconditions_from_other_aggregates()
    {
        $id = $this->aggregateRootId();
        $this->on(UuidAggregateRootId::create())->stage(
            new DummyIncrementingHappened($this->pointInTime(), 10)
        )
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened($this->pointInTime(), 1));
    }

    /**
     * @test
     */
    public function messages_have_a_sequence()
    {
        $id = $this->aggregateRootId();
        $this->given(
            new DummyIncrementingHappened($this->pointInTime(), 1),
            new DummyIncrementingHappened($this->pointInTime(), 1),
            new DummyIncrementingHappened($this->pointInTime(), 1),
            new DummyIncrementingHappened($this->pointInTime(), 1)
        )
            ->when(new EmitSequence($id))
            ->then(new SequenceWasEmit($this->pointInTime(), 4));

        /** @var Message $lastMessage */
        $lastMessage = null;

        foreach ($this->messageRepository->retrieveAll($id) as $message) {
            $lastMessage = $message;
        }

        $this->assertInstanceOf(Message::class, $lastMessage);
        $this->assertEquals(5, $lastMessage->header(Header::AGGREGATE_ROOT_VERSION));
    }

    protected function handle($command)
    {
        $commandHandler = $this->commandHandler($this->repository, $this->clock());
        $commandHandler->handle($command);
    }

    protected function aggregateRootId(): AggregateRootId
    {
        static $id;

        if ( ! $id instanceof AggregateRootId) {
            $id = UuidAggregateRootId::create();
        }

        return $id;
    }
}