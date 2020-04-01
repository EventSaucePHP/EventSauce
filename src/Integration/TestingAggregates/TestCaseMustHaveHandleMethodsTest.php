<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use EventSauce\EventSourcing\DummyAggregateRootId;
use LogicException;

class TestCaseMustHaveHandleMethodsTest extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return DummyAggregateRootId::generate();
    }

    protected function aggregateRootClassName(): string
    {
        return DummyAggregate::class;
    }

    /**
     * @test
     */
    public function missing_handle_methods_result_in_logic_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->when(new DummyCommand($this->aggregateRootId()));
        $this->assertScenario();
    }
}
