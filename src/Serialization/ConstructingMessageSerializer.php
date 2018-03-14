<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
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
        $headers = $message->headers();

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

        /** @var Event $className */
        $className = $this->classNameInflector->typeToClassName($payload['headers'][Header::EVENT_TYPE]);
        $event = $className::fromPayload($payload['payload']);

        yield new Message($event, $payload['headers']);
    }
}
