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
     * @phpstan-param AggregateRootRepository<AggregateRoot> $repository
     */
    public function __construct(
        private AggregateRootId $id,
        private InMemoryMessageRepository $messageRepository,
        private AggregateRootRepository $repository,
        private AggregateRootTestCase $testCase
    ) {
    }

    public function stage(object ...$events): AggregateRootTestCase
    {
        $this->repository->persistEvents($this->id, count($events), ...$events);
        $this->messageRepository->purgeLastCommit();

        return $this->testCase;
    }
}
