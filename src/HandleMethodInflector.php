<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface HandleMethodInflector
{
    /**
     * @return string[]
     */
    public function handleMethods(object $consumer, Message $message): array;
}
