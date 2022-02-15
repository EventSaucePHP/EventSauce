<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;

interface MessageFilter
{
    public function allows(Message $message): bool;
}
