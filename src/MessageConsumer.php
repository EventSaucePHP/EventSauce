<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageConsumer
{
    /**
     * @return void
     */
    public function handle(Message $message);
}
