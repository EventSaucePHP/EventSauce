<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;

class AllowMessagesWithPayloadOfType implements MessageFilter
{
    /** @var string[] */
    private array $classNames;

    public function __construct(string ...$classNames)
    {
        $this->classNames = $classNames;
    }

    public function allows(Message $message): bool
    {
        $event = $message->event();

        foreach ($this->classNames as $className) {
            if ($event instanceof $className) {
                return true;
            }
        }

        return false;
    }
}
