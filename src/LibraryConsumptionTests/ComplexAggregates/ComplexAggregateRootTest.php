<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ComplexAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

class ComplexAggregateRootTest extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return new DummyAggregateRootId('identifier');
    }

    protected function aggregateRootClassName(): string
    {
        return ComplexAggregateRoot::class;
    }

    /**
     * @test
     */
    public function causing_a_delegated_action(): void
    {
        $this->given(
            new DelegatedAggregateWasChosen(),
            new DelegatedActionWasPerformed(1),
            new DelegatedAggregateWasDiscarded()
        )->when(
            new CauseDelegatedBehavior($this->aggregateRootId())
        )->then(
            new DelegatedActionWasPerformed(2)
        );
    }

    protected function handle(object $command): void
    {
        if ($command instanceof CauseDelegatedBehavior) {
            /** @var ComplexAggregateRoot $aggregate */
            $aggregate = $this->repository->retrieve($command->id());
            $aggregate->causeDelegatedAction();
            $this->repository->persist($aggregate);
        }
    }
}
