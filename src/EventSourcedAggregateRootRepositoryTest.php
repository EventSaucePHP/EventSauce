<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\TestUtilities\TestingAggregates\DummyAggregate;
use PHPUnit\Framework\TestCase;
use function iterator_to_array;

class EventSourcedAggregateRootRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function aggregate_versions_are_incremented_per_event(): void
    {
        $messageRepository = new InMemoryMessageRepository();
        $repository = new EventSourcedAggregateRootRepository(DummyAggregate::class, $messageRepository);
        /** @var DummyAggregate $aggregate */
        $aggregateRootId = DummyAggregateRootId::generate();
        $aggregate = $repository->retrieve($aggregateRootId);
        $aggregate->increment();
        $aggregate->increment();
        $aggregate->increment();
        $repository->persist($aggregate);

        /** @var Message[] $messages */
        $messages = iterator_to_array($messageRepository->retrieveAll($aggregateRootId));
        self::assertEquals(1, $messages[0]->aggregateVersion());
        self::assertEquals(2, $messages[1]->aggregateVersion());
        self::assertEquals(3, $messages[2]->aggregateVersion());
    }

    /**
     * @test
     */
    public function aggregate_types_are_added_to_messages(): void
    {
        $messageRepository = new InMemoryMessageRepository();
        $repository = new EventSourcedAggregateRootRepository(DummyAggregate::class, $messageRepository);
        /** @var DummyAggregate $aggregate */
        $aggregateRootId = DummyAggregateRootId::generate();
        $aggregate = $repository->retrieve($aggregateRootId);
        $aggregate->increment();
        $aggregate->increment();
        $aggregate->increment();
        $repository->persist($aggregate);

        $expectedAggregateRootClassName =  'event_sauce.event_sourcing.test_utilities.testing_aggregates.dummy_aggregate';

        /** @var Message[] $messages */
        $messages = iterator_to_array($messageRepository->retrieveAll($aggregateRootId));
        self::assertEquals($expectedAggregateRootType, $messages[0]->aggregateRootClassName());
        self::assertEquals($expectedAggregateRootType, $messages[1]->aggregateRootClassName());
        self::assertEquals($expectedAggregateRootType, $messages[2]->aggregateRootClassName());
    }
}
