<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

interface MessageRepository
{
    public function persist(Message ...$messages);

    public function retrieveAll(AggregateRootId $id): Generator;

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator;
}
