<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

interface MessageRepository
{
    /**
     * @return void
     */
    public function persist(Message ...$messages);

    /**
     * @return Generator<Message>
     */
    public function retrieveAll(AggregateRootId $id): Generator;

    /**
     * @return Generator<Message>
     */
    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator;
}
