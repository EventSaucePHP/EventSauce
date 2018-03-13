<?php

namespace Simple\Definition\Group;

use EventSauce\EventSourcing\Event;

final class SomethingHappened implements Event
{
    /**
     * @var string
     */
    private $what;

    /**
     * @var bool
     */
    private $yolo;

    public function __construct(
        string $what,
        bool $yolo
    ) {
        $this->what = $what;
        $this->yolo = $yolo;
    }

    public function what(): string
    {
        return $this->what;
    }

    public function yolo(): bool
    {
        return $this->yolo;
    }
    public static function fromPayload(array $payload): Event
    {
        return new SomethingHappened(
            (string) $payload['what'],
            (bool) $payload['yolo']);
    }

    public function toPayload(): array
    {
        return [
            'what' => (string) $this->what,
            'yolo' => (bool) $this->yolo,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withWhat(string $what): SomethingHappened
    {
        $this->what = $what;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withYolo(bool $yolo): SomethingHappened
    {
        $this->yolo = $yolo;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): SomethingHappened
    {
        return new SomethingHappened(
            (string) 'Example Event',
            (bool) true
        );
    }
}
