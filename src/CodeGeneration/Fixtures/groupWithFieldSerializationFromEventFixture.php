<?php

namespace With\EventFieldSerialization;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class EventName implements SerializableEvent
{
    /**
     * @var string
     */
    private $title;

    public function __construct(
        string $title
    ) {
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new EventName(
            strtolower($payload['title']));
    }

    public function toPayload(): array
    {
        return [
            'title' => strtoupper($this->title),
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withTitle(string $title): EventName
    {
        $clone = clone $this;
        $clone->title = $title;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): EventName
    {
        return new EventName(
            strtolower('Title')
        );
    }
}
