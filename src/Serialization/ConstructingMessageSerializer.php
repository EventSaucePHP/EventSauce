<?php

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PointInTime;
use Generator;

final class ConstructingMessageSerializer implements MessageSerializer
{
    /**
     * @var string
     */
    private $aggregateRootIdClassName;

    public function __construct(string $aggregateRootIdClassName)
    {
        $this->aggregateRootIdClassName = $aggregateRootIdClassName;
    }

    public function serializeMessage(Message $message): array
    {
        $event = $message->event();
        $payload = $event->toPayload();

        return [
            'type' => EventType::fromEvent($event)->toEventName(),
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
        $className = EventType::fromEventType($payload['type'])->toClassName();
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