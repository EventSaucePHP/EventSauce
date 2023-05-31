<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilter;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\OffsetCursor;

class PartitionedSubscriptionProvider implements SubscriptionProvider
{
    public function __construct(
        private MessageRepository $messageRepository,
        private Partitioner $partitioner,
        private int $pageLimit = 100,
    ) {
    }

    public function getEventsSinceCheckpoint(Checkpoint $checkpoint): \Generator
    {
        if ( ! $checkpoint instanceof PartitionedCheckpoint) {
            throw new \InvalidArgumentException('Checkpoint must be an instance of PartitionedCheckpoint');
        }

        $cursor = OffsetCursor::fromOffset($checkpoint->getOffset(), $this->pageLimit);

        $messages = $this->messageRepository->paginate($cursor);

        /** @var Message $message */
        foreach ($messages as $message) {
            $partitionKey = $this->partitioner->getPartitionKey($message);
            if ($partitionKey !== $checkpoint->getPartitionKey()) {
                continue;
            }
            yield $message;
        }

        return $checkpoint->withOffset($messages->getReturn()->offset());
    }
}
