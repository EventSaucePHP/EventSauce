<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

interface MessageRepository
{
    /**
     * @param Message[] ...$messages
     */
    public function persist(Message ...$messages);

    /**
     * @param AggregateRootId $id
     *
     * @return Generator
     */
    public function retrieveAll(AggregateRootId $id): Generator;
}
