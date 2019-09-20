<?php

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\MessageRepository;
use Generator;

interface SeekableMessageRepository extends MessageRepository
{
    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator;
}
