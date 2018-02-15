<?php


namespace EventSauce\EventSourcing;

interface Event
{
    const EVENT_VERSION_PAYLOAD_KEY = '__event_version';

    public function aggregateRootId(): AggregateRootId;

    public function timeOfRecording(): PointInTime;

    public function toPayload(): array;

    public static function fromPayload(array $payload, AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): Event;
}