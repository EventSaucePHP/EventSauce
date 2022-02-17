<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters;

use EventSauce\EventSourcing\Message;

interface MessageFilter
{
    public function allows(Message $message): bool;
}
