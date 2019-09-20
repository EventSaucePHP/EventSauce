<?php

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Snapshotting\SeekableMessageRepository;
use Generator;

class InMemorySeekableMessageRepository extends InMemoryMessageRepository implements SeekableMessageRepository
{
    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        $lastMessage = null;
        foreach ($this->messages as $message) {
            if ($id->toString() === $message->header(Header::AGGREGATE_ROOT_ID)->toString()) {
                yield $message;
                $lastMessage = $message;
            }
        }

        return $lastMessage instanceof Message ? $lastMessage->aggregateVersion() : 0;
    }
}
