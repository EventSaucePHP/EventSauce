<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\EventConsumption;

use EventSauce\EventSourcing\Message;

interface HandleMethodInflector
{
    /**
     * @return string[]
     */
    public function handleMethods(object $consumer, Message $message): array;
}
