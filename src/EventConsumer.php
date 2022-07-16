<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

abstract class EventConsumer implements MessageConsumer
{
    public function handle(Message $message): void
    {
        $methods = $this->getInflector()->getMethodNames($this, $message);

        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                $this->{$method}($message->payload(), $message);
            }
        }
    }

    protected function getInflector(): HandleInflector
    {
        return new InflectHandlersFromClassName();
    }
}
