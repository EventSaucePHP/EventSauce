<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

interface MessageRepository
{
    /**
     * @throws UnableToPersistMessages
     */
    public function persist(Message ...$messages): void;

    /**
     * @return Generator<Message>
     * @throws UnableToRetrieveMessages
     */
    public function retrieveAll(AggregateRootId $id): Generator;

    /**
     * @return Generator<Message>
     * @throws UnableToRetrieveMessages
     */
    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator;
}
