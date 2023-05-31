<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\OffsetCursor;

class OffsetStreamSubscriptionProvider implements SubscriptionProvider
{
    public function __construct(
        private MessageRepository $messageRepository,
    ) {
    }

    public function getEventsSinceCheckpoint(Checkpoint $checkpoint, int $maxEvents = 100): \Generator
    {
        if ( ! $checkpoint instanceof OffsetCheckpoint) {
            throw new \Exception('Invalid checkpoint type');
        }

        $cursor = OffsetCursor::fromOffset($checkpoint->getOffset(), $maxEvents);
        $messages = $this->messageRepository->paginate($cursor);

        yield from $messages;

        return OffsetCheckpoint::forOffset($cursor->offset());
    }
}
