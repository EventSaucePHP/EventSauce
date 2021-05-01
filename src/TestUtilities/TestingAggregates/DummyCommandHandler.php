<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootRepository;

use function get_class;

/**
 * @testAsset
 */
class DummyCommandHandler
{
    private array $commandToMethodMap = [
        PerformDummyTask::class => 'performDummyTask',
        IgnoredCommand::class => 'dontDoAnything',
        ExceptionInducingCommand::class => 'throwAnException',
        DummyIncrementCommand::class => 'increment',
    ];

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
            /** @var string $method */
            $method = $this->commandToMethodMap[get_class($command)];
            $aggregate->{$method}();
        } finally {
            if (isset($aggregate)) {
                $this->repository->persist($aggregate);
            }
        }
    }
}
