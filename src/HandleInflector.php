<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface HandleInflector
{
    /**
     * @return string[]
     */
    public function getMethodNames(object $consumer, Message $message): array;
}
