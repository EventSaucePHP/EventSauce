<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

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

    public function __construct(AggregateRootId $id, InMemoryMessageRepository $messageRepository, AggregateRootRepository $repository, AggregateRootTestCase $testCase)
    {
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
