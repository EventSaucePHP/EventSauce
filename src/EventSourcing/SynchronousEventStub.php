<?php

namespace EventSauce\EventSourcing;

/**
 * @codeCoverageIgnore
 */
class SynchronousEventStub implements Event
{
    public function aggregateRootId(): AggregateRootId
    {

    }

    public function eventVersion(): int
    {

    }

    public function timeOfRecording(): PointInTime
    {

    }

    public function toPayload(): array
    {

    }

    public static function fromPayload(array $payload, AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): Event
    {

    }
}