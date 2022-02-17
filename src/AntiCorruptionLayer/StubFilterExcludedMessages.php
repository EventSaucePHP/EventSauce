<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;

/**
 * @testAsset
 * @codeCoverageIgnore
 */
class StubFilterExcludedMessages implements MessageFilter
{
    public function allows(Message $message): bool
    {
        return ! $message->event() instanceof StubExcludedEvent;
    }
}
