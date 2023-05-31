<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\MessageRepository;

class AggregateRootIdVersionSubscriptionProvider implements SubscriptionProvider
{
    public function __construct(
        private MessageRepository $messageRepository,
    ) {
    }

    public function getEventsSinceCheckpoint(Checkpoint $checkpoint): \Generator
    {
        if ( ! $checkpoint instanceof AggregateCheckpoint) {
            throw new \InvalidArgumentException('Checkpoint must be an instance of AggregateCheckpoint');
        }

        $messages = $this->messageRepository->retrieveAllAfterVersion($checkpoint->getAggregateRootId(), $checkpoint->getVersion());

        yield from $messages;

        $version = $messages->getReturn() ?? $checkpoint->getVersion();
        if ( ! is_int($version)) {
            throw new \InvalidArgumentException('Version must be an integer');
        }

        return AggregateCheckpoint::forAggregateRootId($checkpoint->getAggregateRootId(), $version);
    }
}
