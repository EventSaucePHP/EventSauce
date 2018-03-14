<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageDecorator
{
    public function decorate(Message $message): Message;
}
