<?php

namespace With\Versioned\Event;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class VersionTwo implements Event
{
    public function __construct(

    ) {

    }

    public static function fromPayload(array $payload): Event
    {
        return new VersionTwo();
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function with(): VersionTwo
    {
        return new VersionTwo(
            
        );
    }

}
