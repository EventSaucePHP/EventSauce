<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\Subscriptions\Checkpoint;
use EventSauce\EventSourcing\Subscriptions\SubscriptionProvider;

class ProjectionHandler
{
    public function __construct(
        private SubscriptionProvider $subscription,
        private MessageConsumer $consumer,
        private ProjectionStatusRepository $projectionStatusRepository,
    ) {
    }

    public function handle(ProjectionId $projectionId, Checkpoint $initialCheckpoint): void
    {
        $cursor = $this->projectionStatusRepository->getCheckpointAndLock($projectionId) ?? $initialCheckpoint;

        $messages = $this->subscription->getEventsSinceCheckpoint($cursor);

        /** @var Message $message */
        foreach ($messages as $message) {
            $this->consumer->handle($message);
        }

        $this->projectionStatusRepository->persistCheckpointAndRelease($projectionId, $messages->getReturn());
    }
}
