<?php

namespace Without\Fields;

use EventSauce\EventSourcing\Event;

final class WithoutFields implements Event
{
    public static function fromPayload(array $payload): Event
    {
        return new WithoutFields();
    }

    public function toPayload(): array
    {
        return [];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): WithoutFields
    {
        return new WithoutFields();
    }
}
