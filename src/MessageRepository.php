<?php

namespace EventSauce\EventSourcing;

use Generator;

interface MessageRepository
{
    public function persist(AggregateRootId $id, Message ... $messages);
    public function retrieveAll(AggregateRootId $id): Generator;
}