<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface MessageDecorator
{
    /**
     * @param Message $message
     *
     * @return Message
     */
    public function decorate(Message $message): Message;
}
