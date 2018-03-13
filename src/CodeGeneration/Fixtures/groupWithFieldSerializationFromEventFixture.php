<?php

namespace With\EventFieldSerialization;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class EventName implements Event
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

    public static function fromPayload(array $payload): Event
    {
        return new EventName(,
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
        $this->title = $title;

        return $this;
    }

    public static function with(): EventName
    {
        return new EventName(
            strtolower('Title')
        );
    }

}
