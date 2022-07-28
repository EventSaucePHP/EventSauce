<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayingMessages;

interface TriggerBeforeReplay
{
    public function beforeReplay(): void;
}
