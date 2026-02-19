<?php

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\CollectingMessageConsumer;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\OffsetCursor;
use EventSauce\EventSourcing\Subscriptions\OffsetCheckpoint;
use EventSauce\EventSourcing\Subscriptions\OffsetStreamSubscriptionProvider;
use PHPUnit\Framework\TestCase;

class ProjectionHandlerTest extends TestCase
{
    /** @test */
    public function it_uses_initial_cursor_when_no_projection_state_exists()
    {
        $repository = new InMemoryMessageRepository();
        $repository->persist(
            new Message(new EventStub('1')),
        );

        $consumer = new CollectingMessageConsumer();

        $projectionStatusRepository = new InMemoryProjectionStatusRepository();

        $projectionHandler = new ProjectionHandler(
            subscription: new OffsetStreamSubscriptionProvider($repository),
            consumer: $consumer,
            projectionStatusRepository: $projectionStatusRepository,
        );

        $projectionHandler->handle($this->getProjectionId(), OffsetCheckpoint::fromStart());

        $this->assertCount(1, $consumer->collectedMessages());
        $this->assertEquals(OffsetCheckpoint::forOffset(1), $projectionStatusRepository->getCursor($this->getProjectionId()));
    }

    /** @test */
    public function it_cant_start_a_new_play_while_projection_is_playing()
    {
        $repository = new InMemoryMessageRepository();
        $repository->persist(
            new Message(new EventStub('1')),
        );

        $consumer = new CollectingMessageConsumer();

        $projectionStatusRepository = new InMemoryProjectionStatusRepository();
        $projectionStatusRepository->getCheckpointAndLock($this->getProjectionId());

        $projectionHandler = new ProjectionHandler(
            subscription: new OffsetStreamSubscriptionProvider($repository),
            consumer: $consumer,
            projectionStatusRepository: $projectionStatusRepository,
        );

        $this->expectExceptionObject(CantLockProjection::becauseItIsAlreadyLocked('test'));
        $projectionHandler->handle($this->getProjectionId(), OffsetCheckpoint::fromStart());
    }

    private function getProjectionId(): ProjectionId
    {
        return ProjectionId::fromString('test');
    }
}
