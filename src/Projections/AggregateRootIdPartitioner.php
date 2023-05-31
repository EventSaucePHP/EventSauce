<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\Message;

class AggregateRootIdPartitioner implements Partitioner
{
    private ClassNameInflector $classNameInflector;

    public function __construct(
        ?ClassNameInflector $classNameInflector = null,
    ) {
        $this->classNameInflector = $classNameInflector ?: new DotSeparatedSnakeCaseInflector();
    }

    public function getPartitionKey(Message $message): string
    {
        $aggregateRootId = $message->aggregateRootId();
        if ($aggregateRootId === null) {
            return 'no_id';
        }

        return $this->classNameInflector->instanceToType($aggregateRootId) . '_' . $aggregateRootId->toString();
    }
}
