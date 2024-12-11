<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;
use Throwable;

use function assert;
use function count;

/**
 * @template T of AggregateRoot
 *
 * @template-implements AggregateRootRepository<T>
 */
class EventSourcedAggregateRootRepository implements AggregateRootRepository
{
    /** @var class-string<T> */
    private string $aggregateRootClassName;
    private MessageRepository $messages;
    private MessageDecorator $decorator;
    private MessageDispatcher $dispatcher;
    private ClassNameInflector $classNameInflector;

    /**
     * @param class-string<T> $aggregateRootClassName
     */
    public function __construct(
        string $aggregateRootClassName,
        MessageRepository $messageRepository,
        ?MessageDispatcher $dispatcher = null,
        ?MessageDecorator $decorator = null,
        ?ClassNameInflector $classNameInflector = null
    ) {
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->messages = $messageRepository;
        $this->dispatcher = $dispatcher ?: new SynchronousMessageDispatcher();
        $this->decorator = $decorator ?: new DefaultHeadersDecorator();
        $this->classNameInflector = $classNameInflector ?: new DotSeparatedSnakeCaseInflector();
    }

    /**
     * @return T
     */
    public function retrieve(AggregateRootId $aggregateRootId): object
    {
        try {
            /** @var AggregateRoot $className */
            /** @phpstan-var class-string<T> $className */
            $className = $this->aggregateRootClassName;
            $events = $this->retrieveAllEvents($aggregateRootId);

            return $className::reconstituteFromEvents($aggregateRootId, $events);
        } catch (Throwable $throwable) {
            throw UnableToReconstituteAggregateRoot::becauseOf($throwable->getMessage(), $throwable);
        }
    }

    private function retrieveAllEvents(AggregateRootId $aggregateRootId): Generator
    {
        /** @var Generator<Message> $messages */
        $messages = $this->messages->retrieveAll($aggregateRootId);

        foreach ($messages as $message) {
            yield $message->event();
        }

        return $messages->getReturn();
    }

    public function persist(object $aggregateRoot): void
    {
        assert($aggregateRoot instanceof AggregateRoot, 'Expected $aggregateRoot to be an instance of ' . AggregateRoot::class);

        $this->persistEvents(
            $aggregateRoot->aggregateRootId(),
            $aggregateRoot->aggregateRootVersion(),
            ...$aggregateRoot->releaseEvents()
        );
    }

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events): void
    {
        if (count($events) === 0) {
            return;
        }

        // decrease the aggregate root version by the number of raised events
        // so the version of each message represents the version at the time
        // of recording.
        $aggregateRootVersion = $aggregateRootVersion - count($events);
        $metadata = [
            Header::AGGREGATE_ROOT_ID => $aggregateRootId,
            Header::AGGREGATE_ROOT_TYPE => $this->classNameInflector->classNameToType($this->aggregateRootClassName),
        ];
        $messages = array_map(function (object $event) use ($metadata, &$aggregateRootVersion) {
            return $this->decorator->decorate(new Message(
                $event,
                $metadata + [Header::AGGREGATE_ROOT_VERSION => ++$aggregateRootVersion]
            ));
        }, $events);

        $this->messages->persist(...$messages);
        $this->dispatcher->dispatch(...$messages);
    }
}
