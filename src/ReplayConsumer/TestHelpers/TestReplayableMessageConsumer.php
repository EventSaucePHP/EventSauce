<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayConsumer\TestHelpers;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\ReplayConsumer\ReplayableMessageConsumer;
use LogicException;

class TestReplayableMessageConsumer implements ReplayableMessageConsumer
{
    private int $failAfterNumberOfMessages;
    private int $numberOfMessagesProcessed = 0;
    private bool $beforeReplayCalled = false;

    public function __construct(int $failAfterNumberOfMessages)
    {
        $this->failAfterNumberOfMessages = $failAfterNumberOfMessages;
    }

    public function handle(Message $message): void
    {
        if (++$this->numberOfMessagesProcessed === $this->failAfterNumberOfMessages) {
            throw new LogicException('Too many messages');
        }
    }

    public function numberOfMessagesProcessed(): int
    {
        return $this->numberOfMessagesProcessed;
    }

    public function beforeReplay(): void
    {
        $this->beforeReplayCalled = true;
    }

    public function beforeReplayIsCalled(): bool
    {
        return $this->beforeReplayCalled;
    }
}
