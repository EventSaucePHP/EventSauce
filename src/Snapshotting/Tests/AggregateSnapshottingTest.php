<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\ConstructingAggregateRootRepository;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\Snapshotting\ConstructingAggregateRootRepositoryWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\InMemorySnapshotRepository;
use EventSauce\EventSourcing\Snapshotting\Snapshot;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

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
    public function testing_snapshotting(): void
    {
        $this->given(LightSwitchWasFlipped::off());
        /** @var LightSwitch $lightSwitch */
        $lightSwitch = $this->repository->retrieveFromSnapshot($this->aggregateRootId);
        $this->assertInstanceOf(LightSwitch::class, $lightSwitch);
        $lightSwitch->turnOn();
        $this->repository->storeSnapshot($lightSwitch);
        $this->assertEquals(true, $lightSwitch->state());

        /** @var LightSwitch $lightSwitchFromSnapshot */
        $lightSwitchFromSnapshot = $this->repository->retrieveFromSnapshot($this->aggregateRootId);
        $this->assertEquals(2, $lightSwitchFromSnapshot->aggregateRootVersion());

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

    protected function aggregateRootRepository(string $className, MessageRepository $repository, MessageDispatcher $dispatcher, MessageDecorator $decorator): AggregateRootRepository
    {
        $this->snapshotRepository = new InMemorySnapshotRepository();

        return new ConstructingAggregateRootRepositoryWithSnapshotting(
            $className,
            $repository,
            $this->snapshotRepository,
            new ConstructingAggregateRootRepository(
                $className,
                $repository,
                $dispatcher,
                $decorator
            )
        );
    }
}
