<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use Generator;

final class ConstructingMessageSerializer implements MessageSerializer
{
    /**
     * @var ClassNameInflector
     */
    private $classNameInflector;

    /**
     * @var PayloadSerializer
     */
    private $payloadSerializer;

    public function __construct(
        ClassNameInflector $classNameInflector = null,
        PayloadSerializer $payloadSerializer = null
    ) {
        $this->classNameInflector = $classNameInflector ?: new DotSeparatedSnakeCaseInflector();
        $this->payloadSerializer = $payloadSerializer ?: new ConstructingPayloadSerializer();
    }

    public function serializeMessage(Message $message): array
    {
        $event = $message->event();
        $payload = $this->payloadSerializer->serializePayload($event);
        $headers = $message->headers();
        $aggregateRootId = $headers[Header::AGGREGATE_ROOT_ID] ?? null;

        if ($aggregateRootId instanceof AggregateRootId) {
            $headers[Header::AGGREGATE_ROOT_ID_TYPE] = $this->classNameInflector->instanceToType($aggregateRootId);
            $headers[Header::AGGREGATE_ROOT_ID] = $aggregateRootId->toString();
        }

        return [
            'headers' => $headers,
            'payload' => $payload,
        ];
    }

    public function unserializePayload(array $payload): Generator
    {
        if (isset($payload['headers'][Header::AGGREGATE_ROOT_ID], $payload['headers'][Header::AGGREGATE_ROOT_ID_TYPE])) {
            /** @var AggregateRootId $aggregateRootIdClassName */
            $aggregateRootIdClassName = $this->classNameInflector->typeToClassName($payload['headers'][Header::AGGREGATE_ROOT_ID_TYPE]);
            $payload['headers'][Header::AGGREGATE_ROOT_ID] = $aggregateRootIdClassName::fromString($payload['headers'][Header::AGGREGATE_ROOT_ID]);
        }

        $className = $this->classNameInflector->typeToClassName($payload['headers'][Header::EVENT_TYPE]);
        $event = $this->payloadSerializer->unserializePayload($className, $payload['payload']);

        yield new Message($event, $payload['headers']);
    }
}
