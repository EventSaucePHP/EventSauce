<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\OffsetCursor;

class OffsetStreamSubscriptionProvider implements SubscriptionProvider
{
    public function __construct(
        private MessageRepository $messageRepository,
        private int $pageLimit = 100,
    ) {
    }

    public function getEventsSinceCheckpoint(Checkpoint $checkpoint): \Generator
    {
        if ( ! $checkpoint instanceof OffsetCheckpoint) {
            throw new \Exception('Invalid checkpoint type');
        }

        $cursor = OffsetCursor::fromOffset($checkpoint->getOffset(), $this->pageLimit);
        $messages = $this->messageRepository->paginate($cursor);

        yield from $messages;

        return OffsetCheckpoint::forOffset($messages->getReturn()->offset());
    }
}
