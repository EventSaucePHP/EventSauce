<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\RequiringHistoryWithAggregateRootConstruction;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\ConstructingAggregateRootRepository;
use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\InvalidAggregateRootReconstitutionException;
use PHPUnit\Framework\TestCase;

class AggregateRootConstructionTest extends TestCase
{
    /**
     * @test
     */
    public function expecting_an_exception_without_history(): void
    {
        $this->expectException(InvalidAggregateRootReconstitutionException::class);
        $repository = new ConstructingAggregateRootRepository(
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
        $repository = new ConstructingAggregateRootRepository(
            AggregateThatRequiredHistoryForReconstitutionStub::class,
            new InMemoryMessageRepository()
        );
        $id = DummyAggregateRootId::fromString('nope');
        $repository->persistEvents($id, 1, new DummyInternalEvent());
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
