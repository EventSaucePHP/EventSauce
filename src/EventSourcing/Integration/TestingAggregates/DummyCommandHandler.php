<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\CommandHandler;
use EventSauce\Time\Clock;

class DummyCommandHandler implements CommandHandler
{
    /**
     * @var AggregateRootRepository
     */
    private $repository;

    /**
     * @var Clock
     */
    private $clock;

    public function __construct(AggregateRootRepository $repository, Clock $clock)
    {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    public function handle(Command $command)
    {
        /** @var DummyAggregate $aggregate */
        $aggregate = $this->repository->retrieve($command->aggregateRootId());

        try {
            if ($command instanceof DummyCommand) {
                $aggregate->performDummyTask($this->clock);
            } elseif ($command instanceof IgnoredCommand) {
                $aggregate->dontDoAnything();
            } elseif ($command instanceof ExceptionInducingCommand) {
                $aggregate->throwAnException();
            } elseif ($command instanceof DummyIncrementCommand) {
                $aggregate->increment($this->clock);
            }
        } finally {
            $this->repository->persist(... $aggregate->releaseEvents());
        }
    }
}