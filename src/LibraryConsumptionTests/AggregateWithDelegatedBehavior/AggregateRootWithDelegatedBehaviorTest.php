<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\AggregateWithDelegatedBehavior;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

/**
 * @method DummyAggregateRootId aggregateRootId()
 */
class AggregateRootWithDelegatedBehaviorTest extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return new DummyAggregateRootId('identifier');
    }

    protected function aggregateRootClassName(): string
    {
        return AggregateRootWithDelegatedBehavior::class;
    }

    /**
     * @test
     */
    public function causing_a_delegated_action(): void
    {
        $this->given(
            new DelegatedAggregateWasChosen(),
            new DelegatedActionWasPerformed(1),
        )->when(
            new CauseDelegatedBehavior($this->aggregateRootId())
        )->then(
            new DelegatedActionWasPerformed(2)
        );
    }

    /**
     * @test
     */
    public function a_delegated_aggregate_doesnt_apply_events_when_its_not_registered(): void
    {
        $this->given(
            new DelegatedActionWasPerformed(1),
            new DelegatedAggregateWasChosen(),
        )->when(
            new CauseDelegatedBehavior($this->aggregateRootId())
        )->then(
            new DelegatedActionWasPerformed(1)
        );
    }

    protected function handle(object $command): void
    {
        if ($command instanceof CauseDelegatedBehavior) {
            /** @var AggregateRootWithDelegatedBehavior $aggregate */
            $aggregate = $this->repository->retrieve($command->id());
            $aggregate->causeDelegatedAction();
            $this->repository->persist($aggregate);
        }
    }
}
