<?php

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\MessageDispatcher;

interface MessageDispatcherWithBeforeReplay extends MessageDispatcher
{
    public function beforeReplay(): void;
}
