<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface Event
{
    public function toPayload(): array;

    public static function fromPayload(array $payload): Event;
}
