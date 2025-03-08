<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\RequiringHistoryWithAggregateRootConstruction;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\UnableToReconstituteAggregateRoot;
use PHPUnit\Framework\TestCase;

class AggregateRootConstructionTest extends TestCase
{
    /**
     * @test
     */
    public function expecting_an_exception_without_history(): void
    {
        $this->expectException(UnableToReconstituteAggregateRoot::class);
        $repository = new EventSourcedAggregateRootRepository(
            AggregateThatRequiredHistoryForReconstitutionStub::class,
            new InMemoryMessageRepository()
        );

        $repository->retrieve(DummyAggregateRootId::fromString('nope'));
    }

    /**
     * @test
     */
    public function expecting_an_aggregate_when_there_is_history(): void
    {
        $repository = new EventSourcedAggregateRootRepository(
            AggregateThatRequiredHistoryForReconstitutionStub::class,
            new InMemoryMessageRepository()
        );
        $id = DummyAggregateRootId::fromString('nope');
        $repository->persistEvents($id, 1, [new DummyInternalEvent()]);
        $aggregateRoot = $repository->retrieve($id);
        $this->assertInstanceOf(AggregateThatRequiredHistoryForReconstitutionStub::class, $aggregateRoot);
    }

    /**
     * @test
     */
    public function constructing_the_aggregate_using_a_named_constructor(): void
    {
        $id = DummyAggregateRootId::fromString('nope');
        $aggregate = AggregateThatRequiredHistoryForReconstitutionStub::start($id);
        $this->assertInstanceOf(AggregateRoot::class, $aggregate);
    }
}
