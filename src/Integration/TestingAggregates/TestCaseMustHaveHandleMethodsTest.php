<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use EventSauce\EventSourcing\UuidAggregateRootId;
use LogicException;

class TestCaseMustHaveHandleMethodsTest extends AggregateRootTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function newAggregateRootId(): AggregateRootId
    {
        return UuidAggregateRootId::create();
    }

    protected function aggregateRootClassName(): string
    {
        return DummyAggregate::class;
    }

    /**
     * @test
     */
    public function missing_handle_methods_result_in_logic_exception()
    {
        $this->expectException(LogicException::class);
        $this->when(new DummyCommand($this->aggregateRootId()));
        $this->assertScenario();
    }
}
