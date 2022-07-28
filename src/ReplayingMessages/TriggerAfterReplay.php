<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayingMessages;

interface TriggerAfterReplay
{
    public function afterReplay(): void;
}
