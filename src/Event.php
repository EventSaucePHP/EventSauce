<?php

namespace EventSauce\EventSourcing;

use JsonSerializable;

interface Event extends JsonSerializable
{
    public function timeOfRecording(): PointInTime;

    public function toPayload(): array;

    public static function fromPayload(array $payload, PointInTime $timeOfRecording): Event;
}