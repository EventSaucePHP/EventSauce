<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\EventStub;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use Generator;

class AggregateRootIdVersionSubscriptionProvider implements SubscriptionProvider
{

    public function __construct(
        private MessageRepository $messageRepository,
    )
    {
    }

    public function getEventsSinceCheckpoint(Checkpoint $checkpoint): Generator
    {
        if(!$checkpoint instanceof AggregateCheckpoint){
            throw new \InvalidArgumentException('Checkpoint must be an instance of AggregateCheckpoint');
        }

        $messages = $this->messageRepository->retrieveAllAfterVersion($checkpoint->getAggregateRootId(), $checkpoint->getVersion());

        yield from $messages;

        return AggregateCheckpoint::forAggregateRootId($checkpoint->getAggregateRootId(), $messages->getReturn() ?? $checkpoint->getVersion());
    }
}
