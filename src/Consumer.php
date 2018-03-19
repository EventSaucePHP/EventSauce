<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface Consumer
{
    /**
     * @param Message $message
     */
    public function handle(Message $message);
}
