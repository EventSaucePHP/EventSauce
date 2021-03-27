<?php

declare(strict_types=1);

namespace Simple\Definition\Group;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class SomethingHappened implements SerializablePayload
{
    public function __construct(
        private string $what,
        private bool $yolo
    ) {
    }

    public function what(): string
    {
        return $this->what;
    }

    public function yolo(): bool
    {
        return $this->yolo;
    }

    public static function fromPayload(array $payload): self
    {
        return new SomethingHappened(
            (string) $payload['what'],
            (bool) $payload['yolo']
        );
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
    public static function withDefaults(): SomethingHappened
    {
        return new SomethingHappened(
            (string) 'Example Event',
            (bool) true
        );
    }
}
