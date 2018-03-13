<?php

namespace EventSauce\EventSourcing;

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

    /**
     * @return AggregateRootTestCase
     */
    public function stage(Event ... $events): AggregateRootTestCase
    {
        $this->repository->persistEvents($this->id, 0, ... $events);
        $this->messageRepository->purgeLastCommit();

        return $this->testCase;
    }
}