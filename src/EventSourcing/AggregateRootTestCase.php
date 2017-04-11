<?php

namespace EventSauce\EventSourcing;

use Exception;
use PHPUnit\Framework\TestCase;
use EventSauce\Time\Clock;
use EventSauce\Time\TestClock;

abstract class AggregateRootTestCase extends TestCase
{
    /**
     * @var TestMessageRepository
     */
    private $eventStore;

    /**
     * @var AggregateRootRepository
     */
    private $repository;

    /**
     * @var CommandHandler
     */
    private $commandHandler;

    /**
     * @var Exception
     */
    private $caughtException;

    /**
     * @var Event[]
     */
    private $expectedEvents = [];

    /**
     * @var Exception
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
     * @before
     */
    protected function setUpEventStore()
    {
        $className = $this->aggregateRootClassName();
        $this->clock = new TestClock();
        $this->eventStore = new TestMessageRepository();
        $this->repository = new AggregateRootRepository($className, $this->eventStore, new DelegatingMessageDecorator());
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

        $this->assertExpectedException($this->theExpectedException, $this->caughtException);
        $this->assertLastCommitEqualsEvents(... $this->expectedEvents);
        $this->eventStore->purgeLastCommit();
        $this->assertedScenario = true;
    }

    abstract protected function aggregateRootClassName(): string;

    abstract protected function commandHandler(AggregateRootRepository $repository, Clock $clock): CommandHandler;

    /**
     * @return $this
     */
    protected function given(Event ... $events)
    {
        $this->repository->persist(... $events);
        $this->eventStore->purgeLastCommit();

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
        self::assertEquals($events, $this->eventStore->lastCommit());
    }

    private function assertExpectedException(Exception $expectedException = null, Exception $caughtException = null)
    {
        $this->theExpectedException = null;
        $this->caughtException = null;

        if ($expectedException == $caughtException) {
            return;
        }

        if ($expectedException === null) {
            throw $caughtException;
        }

        self::assertEquals([$expectedException], [$caughtException]);
    }

    protected function pointInTime(): PointInTime
    {
        return $this->clock->pointInTime();
    }
}