<?php

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PointInTime;
use Generator;

final class ConstructingMessageSerializer implements MessageSerializer
{
    /**
     * @var ClassNameInflector
     */
    private $classNameInflector;

    public function __construct(ClassNameInflector $classNameInflector = null)
    {
        $this->classNameInflector = $classNameInflector ?: new DotSeparatedSnakeCaseInflector();
    }

    public function serializeMessage(Message $message): array
    {
        $event = $message->event();
        $payload = $event->toPayload();
        $aggregateRootId = $message->metadataValue('aggregate_root_id');

        if ($aggregateRootId instanceof AggregateRootId) {
            $message = $message->withMetadata('aggregate_root_id', $aggregateRootId->toString())
                ->withMetadata('aggregate_root_id_type', $this->classNameInflector->instanceToType($aggregateRootId));
        }

        return [
            'type'            => $this->classNameInflector->instanceToType($event),
            'version'         => $payload[Event::EVENT_VERSION_PAYLOAD_KEY] ?? 0,
            'timeOfRecording' => $event->timeOfRecording()->toString(),
            'metadata'        => $message->metadata(),
            'data'            => $payload,
        ];
    }

    public function unserializePayload(array $payload): Generator
    {
        /** @var Event $className */
        $className = $this->classNameInflector->typeToClassName($payload['type']);

        if (isset($payload['metadata']['aggregate_root_id'], $payload['metadata']['aggregate_root_id_type'])) {
            /** @var AggregateRootId $aggregateRootIdClassName */
            $aggregateRootIdClassName = $this->classNameInflector->typeToClassName($payload['metadata']['aggregate_root_id_type']);
            $payload['metadata']['aggregate_root_id'] = $aggregateRootIdClassName::fromString($payload['metadata']['aggregate_root_id']);
        }

        $event = $className::fromPayload(
            $payload['data'],
            PointInTime::fromString($payload['timeOfRecording'])
        );

        yield new Message($event, $payload['metadata']);
    }
}