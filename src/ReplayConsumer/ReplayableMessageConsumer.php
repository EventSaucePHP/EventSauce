<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\MessageConsumer;

interface ReplayableMessageConsumer extends MessageConsumer
{
    public function beforeReplay(): void;
}
