<?php

namespace EventSauce\EventSourcing;

use Exception;
use function get_class;
use PHPUnit\Framework\TestCase;
use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\Time\TestClock;

abstract class AggregateRootTestCase extends TestCase
{
    /**
     * @var InMemoryMessageRepository
     */
    private $messageRepository;

    /**
     * @var AggregateRootRepository
     */
    private $repository;

    /**
     * @var CommandHandler
     */
    private $commandHandler;

    /**
     * @var Exception|null
     */
    private $caughtException;

    /**
     * @var Event[]
     */
    private $expectedEvents = [];

    /**
     * @var Exception|null
     */
    private $theExpectedException;

    /**
     * @var TestClock
     */
    private $clock;

    /**
     * @var bool
     */
    private $assertedScenario = false;

    /**
     * @var AggregateRootId
     */
    protected $aggregateRootId;

    /**
     * @before
     */
    protected function setUpEventStore()
    {
        $className = $this->aggregateRootClassName();
        $this->clock = new TestClock();
        $this->aggregateRootId = $this->aggregateRootId();
        $this->messageRepository = new InMemoryMessageRepository($this->messageDispatcher());
        $this->repository = new AggregateRootRepository($className, $this->messageRepository, new DelegatingMessageDecorator());
        $this->commandHandler = $this->commandHandler($this->repository, $this->clock);
        $this->expectedEvents = [];
        $this->assertedScenario = false;
        $this->theExpectedException = null;
        $this->caughtException = null;
    }

    /**
     * @after
     */
    protected function assertScenario()
    {
        // @codeCoverageIgnoreStart
        if ($this->assertedScenario) {
            return;
        }
        // @codeCoverageIgnoreEnd

        try {
            $this->assertExpectedException($this->theExpectedException, $this->caughtException);
            $this->assertLastCommitEqualsEvents(... $this->expectedEvents);
            $this->messageRepository->purgeLastCommit();
        } finally {
            $this->assertedScenario = true;
            $this->theExpectedException = null;
            $this->caughtException = null;
        }
    }

    abstract protected function aggregateRootId(): AggregateRootId;

    abstract protected function aggregateRootClassName(): string;

    abstract protected function commandHandler(AggregateRootRepository $repository, Clock $clock): CommandHandler;

    /**
     * @return $this
     */
    protected function given(Event ... $events)
    {
        $this->repository->persistEvents(... $events);
        $this->messageRepository->purgeLastCommit();

        return $this;
    }

    /**
     * @return $this
     */
    protected function when(Command $command)
    {
        try {
            $this->commandHandler->handle($command);
        } catch (Exception $exception) {
            $this->caughtException = $exception;
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function then(Event ... $events)
    {
        $this->expectedEvents = $events;

        return $this;
    }

    /**
     * @return $this
     */
    public function thenWeAreSorry(Exception $expectedException)
    {
        $this->theExpectedException = $expectedException;

        return $this;
    }

    /**
     * @return $this
     */
    protected function thenNothingShouldHaveHappened()
    {
        $this->expectedEvents = [];

        return $this;
    }

    protected function assertLastCommitEqualsEvents(Event ... $events)
    {
        self::assertEquals($events, $this->messageRepository->lastCommit(), "Events are not equal.");
    }

    private function assertExpectedException(Exception $expectedException = null, Exception $caughtException = null)
    {
        if ($expectedException == $caughtException) {
            return;
        }

        if ( ! $expectedException instanceof Exception || ($caughtException instanceof Exception && (get_class($expectedException) !== get_class($caughtException)))) {
            throw $caughtException;
        }

        self::assertEquals([$expectedException], [$caughtException], ">> Exceptions are not equal.");
    }

    protected function pointInTime(): PointInTime
    {
        return $this->clock->pointInTime();
    }

    protected function messageDispatcher(): MessageDispatcher
    {
        return new SynchronousMessageDispatcher(... $this->consumers());
    }

    /**
     * @return Consumer[]
     */
    protected function consumers(): array
    {
        return [];
    }
}