<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface Event
{
    /**
     * @return array
     */
    public function toPayload(): array;

    /**
     * @param array $payload
     *
     * @return Event
     */
    public static function fromPayload(array $payload): Event;
}
