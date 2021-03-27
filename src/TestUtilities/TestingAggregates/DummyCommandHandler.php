<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootRepository;

class DummyCommandHandler
{
    /**
     * @phpstan-var AggregateRootRepository<DummyAggregate>
     */
    private AggregateRootRepository $repository;

    /**
     * @phpstan-param AggregateRootRepository<DummyAggregate> $repository
     */
    public function __construct(AggregateRootRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws DummyException
     */
    public function handle(DummyCommand $command): void
    {
        try {
            if ($command instanceof InitiatorCommand) {
                $aggregate = DummyAggregate::create($command->aggregateRootId());

                return;
            }

            /** @var DummyAggregate $aggregate */
            $aggregate = $this->repository->retrieve($command->aggregateRootId());

            if ($command instanceof PerformDummyTask) {
                $aggregate->performDummyTask();
            } elseif ($command instanceof IgnoredCommand) {
                $aggregate->dontDoAnything();
            } elseif ($command instanceof ExceptionInducingCommand) {
                $aggregate->throwAnException();
            } elseif ($command instanceof DummyIncrementCommand) {
                $aggregate->increment();
            }
        } finally {
            $this->repository->persist($aggregate);
        }
    }
}
