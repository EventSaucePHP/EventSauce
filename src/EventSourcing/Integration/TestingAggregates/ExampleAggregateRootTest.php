<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\AggregateRootTestCase;
use EventSauce\EventSourcing\CommandHandler;
use EventSauce\Time\Clock;

class ExampleAggregateRootTest extends AggregateRootTestCase
{
    protected function aggregateRootClassName(): string
    {
        return DummyAggregate::class;
    }

    protected function commandHandler(AggregateRootRepository $repository, Clock $clock): CommandHandler
    {
        return new DummyCommandHandler($repository, $clock);
    }

    /**
     * @test
     */
    public function executing_a_command_sucessfully()
    {
        $aggregateRootId = AggregateRootId::create();
        $this->when(new DummyCommand($aggregateRootId));
        $this->then(new DummyTaskWasExecuted($aggregateRootId, $this->pointInTime()));
    }

    /**
     * @test
     */
    public function asserting_nothing_happened()
    {
        $aggregateRootId = AggregateRootId::create();
        $this->when(new IgnoredCommand($aggregateRootId));
        $this->thenNothingShouldHaveHappened();
    }

    /**
     * @test
     */
    public function expecting_exceptions()
    {
        $this->when(new ExceptionInducingCommand(AggregateRootId::create()))
            ->thenWeAreSorry(new DummyException());
    }

    /**
     * @test
     */
    public function not_expecting_exceptions()
    {
        $this->expectException(DummyException::class);
        $this->when(new ExceptionInducingCommand(AggregateRootId::create()));
        $this->assertScenario();
    }

    /**
     * @test
     */
    public function setting_preconditions()
    {
        $id = AggregateRootId::create();
        $this->given(new DummyIncrementingHappened($id, $this->pointInTime(), 1))
            ->when(new DummyIncrementCommand($id))
            ->then(new DummyIncrementingHappened($id, $this->pointInTime(), 2));
    }
}