<?php

namespace Simple\Definition\Group;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class SomethingHappened implements SerializableEvent
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
    public static function fromPayload(array $payload): SerializableEvent
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
        $clone = clone $this;
        $clone->what = $what;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withYolo(bool $yolo): SomethingHappened
    {
        $clone = clone $this;
        $clone->yolo = $yolo;

        return $clone;
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
