<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageConsumer
{
    public function handle(Message $message);
}
