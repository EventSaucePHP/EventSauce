<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingMessageConsumers;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use LogicException;

class TestedMessageConsumer implements MessageConsumer
{
    private int $failAfterNumberOfMessages;
    private int $numberOfMessagesProcessed = 0;
    private ?AggregateRootId $lastProcessedUuid = null;

    public function __construct(int $failAfterNumberOfMessages)
    {
        $this->failAfterNumberOfMessages = $failAfterNumberOfMessages;
    }

    public function handle(Message $message): void
    {
        if (++$this->numberOfMessagesProcessed === $this->failAfterNumberOfMessages) {
            throw new LogicException('Too many messages');
        }
        $this->lastProcessedUuid = $message->aggregateRootId();
    }

    public function numberOfMessagesProcessed(): int
    {
        return $this->numberOfMessagesProcessed;
    }

    public function lastProcessedUuid(): ?AggregateRootId
    {
        return $this->lastProcessedUuid;
    }
}
