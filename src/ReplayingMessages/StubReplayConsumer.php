<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayingMessages;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class StubReplayConsumer implements MessageConsumer, TriggerAfterReplay, TriggerBeforeReplay
{
    public bool $afterTriggered = false;
    public bool $beforeTriggered = false;
    public int $messagesHandled = 0;

    public function handle(Message $message): void
    {
        $this->messagesHandled++;
    }

    public function afterReplay(): void
    {
        $this->afterTriggered = true;
    }

    public function beforeReplay(): void
    {
        $this->beforeTriggered = true;
    }
}
