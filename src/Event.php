<?php

namespace EventSauce\EventSourcing;

interface Event
{
    public function toPayload(): array;

    public static function fromPayload(array $payload): Event;
}