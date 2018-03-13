<?php

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\Time\TestClock;
use Exception;
use LogicException;
use PHPUnit\Framework\TestCase;
use function get_class;
use function method_exists;
use function sprintf;

/**
 * @method handle()
 */
abstract class AggregateRootTestCase extends TestCase
{
    /**
     * @var InMemoryMessageRepository
     */
    protected $messageRepository;

    /**
     * @var AggregateRootRepository
     */
    protected $repository;

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
        $this->messageRepository = new InMemoryMessageRepository();
        $dispatcher = $this->messageDispatcher();
        $decorator = $this->messageDecorator();
        $this->repository = new AggregateRootRepository($className, $this->messageRepository, $dispatcher, $decorator);
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

    /**
     * @return $this
     */
    protected function given(Event ... $events)
    {
        $this->repository->persistEvents($this->aggregateRootId(), 0, ... $events);
        $this->messageRepository->purgeLastCommit();

        return $this;
    }

    public function on(AggregateRootId $id)
    {
        return new EventStager($id, $this->messageRepository, $this->repository, $this);
    }

    /**
     * @return $this
     */
    protected function when(... $arguments)
    {
        try {
            if ( ! method_exists($this, 'handle')) {
                throw new LogicException(sprintf('Class %s is missing a ::handle method.', get_class($this)));
            }

            $this->handle(...$arguments);
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
    public function expectToFail(Exception $expectedException)
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

    protected function clock(): Clock
    {
        return $this->clock;
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

    private function messageDecorator(): MessageDecorator
    {
        return new MessageDecoratorChain(new DefaultHeadersDecorator());
    }
}