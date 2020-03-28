<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\TestUtilities\MessageConsumerThatSerializesMessages;
use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\Time\TestClock;
use Exception;
use function get_class;
use LogicException;
use function method_exists;
use PHPUnit\Framework\TestCase;
use function sprintf;

/**
 * @method handle(...$arguments)
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
     * @var object[]
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
    protected function setUpEventSauce(): void
    {
        $className = $this->aggregateRootClassName();
        $this->clock = new TestClock();
        $this->aggregateRootId = $this->newAggregateRootId();
        $this->messageRepository = new InMemoryMessageRepository();
        $dispatcher = $this->messageDispatcher();
        $decorator = $this->messageDecorator();
        $this->repository = $this->aggregateRootRepository(
            $className,
            $this->messageRepository,
            $dispatcher,
            $decorator
        );
        $this->expectedEvents = [];
        $this->assertedScenario = false;
        $this->theExpectedException = null;
        $this->caughtException = null;
    }

    protected function retrieveAggregateRoot(AggregateRootId $id): object
    {
        return $this->repository->retrieve($id);
    }

    protected function persistAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        $this->repository->persist($aggregateRoot);
    }

    /**
     * @after
     */
    protected function assertScenario(): void
    {
        // @codeCoverageIgnoreStart
        if ($this->assertedScenario) {
            return;
        }
        // @codeCoverageIgnoreEnd

        try {
            $this->assertExpectedException($this->theExpectedException, $this->caughtException);
            $this->assertLastCommitEqualsEvents(...$this->expectedEvents);
            $this->messageRepository->purgeLastCommit();
        } finally {
            $this->assertedScenario = true;
            $this->theExpectedException = null;
            $this->caughtException = null;
        }
    }

    protected function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    abstract protected function newAggregateRootId(): AggregateRootId;

    abstract protected function aggregateRootClassName(): string;

    /**
     * @return $this
     */
    protected function given(object ...$events)
    {
        $this->repository->persistEvents($this->aggregateRootId(), count($events), ...$events);
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
    protected function when(...$arguments)
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
    protected function then(object ...$events)
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

    protected function assertLastCommitEqualsEvents(object ...$events): void
    {
        self::assertEquals($events, $this->messageRepository->lastCommit(), 'Events are not equal.');
    }

    private function assertExpectedException(Exception $expectedException = null, Exception $caughtException = null): void
    {
        if ($expectedException == $caughtException) {
            return;
        }

        if (
            null !== $caughtException && (
            null === $expectedException ||
            get_class($expectedException) !== get_class($caughtException))
        ) {
            throw $caughtException;
        }

        self::assertEquals([$expectedException], [$caughtException], '>> Exceptions are not equal.');
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
        return new SynchronousMessageDispatcher(
            new MessageConsumerThatSerializesMessages(),
            ...$this->consumers()
        );
    }

    /**
     * @return MessageConsumer[]
     */
    protected function consumers(): array
    {
        return [];
    }

    private function messageDecorator(): MessageDecorator
    {
        return new MessageDecoratorChain(new DefaultHeadersDecorator());
    }

    protected function aggregateRootRepository(
        string $className,
        MessageRepository $repository,
        MessageDispatcher $dispatcher,
        MessageDecorator $decorator
    ): AggregateRootRepository {
        return new ConstructingAggregateRootRepository(
            $className,
            $repository,
            $dispatcher,
            $decorator
        );
    }
}
