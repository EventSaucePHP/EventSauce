<?php

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\AggregateRootTestCase;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\Snapshotting\ConstructingAggregateRootRepositoryWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\InMemorySnapshotRepository;
use EventSauce\EventSourcing\Snapshotting\SeekableMessageRepository;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use LogicException;

class AggregateSnapshottingTest extends AggregateRootTestCase
{
    /**
     * @var InMemorySnapshotRepository
     */
    protected $snapshotRepository;

    /**
     * @var ConstructingAggregateRootRepositoryWithSnapshotting
     */
    protected $repository;

    /**
     * @test
     */
    public function testing_snapshotting()
    {
        /** @var LightSwitch $lightSwitch */
        $lightSwitch = $this->repository->retrieveFromSnapshot($this->aggregateRootId);
        $this->assertInstanceOf(LightSwitch::class, $lightSwitch);
        $lightSwitch->turnOn();
        $this->repository->storeSnapshot($lightSwitch);
        $this->assertEquals(true, $lightSwitch->state());

        /** @var LightSwitch $lightSwitchFromSnapshot */
        $lightSwitchFromSnapshot = $this->repository->retrieveFromSnapshot($this->aggregateRootId);
        $this->assertEquals(1, $lightSwitchFromSnapshot->aggregateRootVersion());

        $lightSwitch->turnOff();
        $this->repository->persist($lightSwitch);
        $this->assertEquals(false, $lightSwitch->state());

        $snapshot = $this->snapshotRepository->retrieve($this->aggregateRootId);
        $this->assertInstanceOf(Snapshot::class, $snapshot);
        $this->assertEquals(true, $snapshot->state());

        /** @var LightSwitch $lightSwitchFromSnapshot */
        $lightSwitchFromSnapshot = $this->repository->retrieveFromSnapshot($this->aggregateRootId);
        $this->assertInstanceOf(LightSwitch::class, $lightSwitchFromSnapshot);
        $this->assertEquals(false, $lightSwitch->state());

        // work around test tooling
        $this->messageRepository->purgeLastCommit();
    }

    protected function newAggregateRootId(): AggregateRootId
    {
        return new LightSwitchId('bedroom');
    }

    protected function aggregateRootClassName(): string
    {
        return LightSwitch::class;
    }

    protected function messageRepository(): InMemoryMessageRepository
    {
        return new InMemorySeekableMessageRepository();
    }

    protected function aggregateRootRepository(string $className, MessageRepository $repository, MessageDispatcher $dispatcher, MessageDecorator $decorator): AggregateRootRepository
    {
        if ( ! $repository instanceof SeekableMessageRepository) {
            throw new LogicException('This test-case requires a seekable message repository.');
        }

        $this->snapshotRepository = new InMemorySnapshotRepository();

        return new ConstructingAggregateRootRepositoryWithSnapshotting(
            $className,
            $repository,
            $this->snapshotRepository,
            $dispatcher,
            $decorator
        );
    }
}
