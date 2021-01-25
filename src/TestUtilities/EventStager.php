<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\InMemoryMessageRepository;

class EventStager
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @phpstan-var AggregateRootRepository<AggregateRoot>
     *
     * @var AggregateRootRepository
     */
    private $repository;

    /**
     * @var AggregateRootTestCase
     */
    private $testCase;

    /**
     * @var InMemoryMessageRepository
     */
    private $messageRepository;

    /**
     * @phpstan-param AggregateRootRepository<AggregateRoot> $repository
     */
    public function __construct(
        AggregateRootId $id,
        InMemoryMessageRepository $messageRepository,
        AggregateRootRepository $repository,
        AggregateRootTestCase $testCase
    ) {
        $this->id = $id;
        $this->repository = $repository;
        $this->testCase = $testCase;
        $this->messageRepository = $messageRepository;
    }

    public function stage(object ...$events): AggregateRootTestCase
    {
        $this->repository->persistEvents($this->id, count($events), ...$events);
        $this->messageRepository->purgeLastCommit();

        return $this->testCase;
    }
}
