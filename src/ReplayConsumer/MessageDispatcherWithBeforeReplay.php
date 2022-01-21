<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\MessageDispatcher;

interface MessageDispatcherWithBeforeReplay extends MessageDispatcher
{
    public function beforeReplay(): void;
}
