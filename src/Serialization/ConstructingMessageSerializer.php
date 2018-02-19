<?php

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\EventNameInflector;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PointInTime;
use Generator;

final class ConstructingMessageSerializer implements MessageSerializer
{
    /**
     * @var string
     */
    private $aggregateRootIdClassName;

    /**
     * @var EventNameInflector
     */
    private $eventNameInflector;

    public function __construct(string $aggregateRootIdClassName, EventNameInflector $eventNameInflector = null)
    {
        $this->aggregateRootIdClassName = $aggregateRootIdClassName;
        $this->eventNameInflector = $eventNameInflector ?: new DotSeparatedSnakeCaseInflector();
    }

    public function serializeMessage(Message $message): array
    {
        $event = $message->event();
        $payload = $event->toPayload();

        return [
            'type' => $this->eventNameInflector->eventToEventName($event),
            'version' => $payload[Event::EVENT_VERSION_PAYLOAD_KEY] ?? 0,
            'aggregateRootId' => $event->aggregateRootId()->toString(),
            'timeOfRecording' => $event->timeOfRecording()->toString(),
            'metadata' => $message->metadata(),
            'data' => $payload,
        ];
    }

    public function unserializePayload(array $payload): Generator
    {
        /** @var Event $className */
        $className = $this->eventNameInflector->eventNameToClassName($payload['type']);
        /** @var AggregateRootId $aggregateRootIdClassName */
        $aggregateRootIdClassName = $this->aggregateRootIdClassName;
        $event = $className::fromPayload(
            $payload['data'],
            $aggregateRootIdClassName::fromString($payload['aggregateRootId']),
            PointInTime::fromString($payload['timeOfRecording'])
        );

        yield new Message($event, $payload['metadata']);
    }
}