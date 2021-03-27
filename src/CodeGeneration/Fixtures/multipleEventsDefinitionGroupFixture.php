<?php

declare(strict_types=1);

namespace Multiple\Events\DefinitionGroup;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class FirstEvent implements SerializablePayload
{
    public function __construct(
        private string $firstField
    ) {
    }

    public function firstField(): string
    {
        return $this->firstField;
    }

    public static function fromPayload(array $payload): self
    {
        return new FirstEvent(
            (string) $payload['firstField']
        );
    }

    public function toPayload(): array
    {
        return [
            'firstField' => (string) $this->firstField,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withFirstField(string $firstField): FirstEvent
    {
        $clone = clone $this;
        $clone->firstField = $firstField;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withDefaults(): FirstEvent
    {
        return new FirstEvent(
            (string) 'FIRST'
        );
    }
}

final class SecondEvent implements SerializablePayload
{
    public function __construct(
        private string $secondField
    ) {
    }

    public function secondField(): string
    {
        return $this->secondField;
    }

    public static function fromPayload(array $payload): self
    {
        return new SecondEvent(
            (string) $payload['secondField']
        );
    }

    public function toPayload(): array
    {
        return [
            'secondField' => (string) $this->secondField,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withSecondField(string $secondField): SecondEvent
    {
        $clone = clone $this;
        $clone->secondField = $secondField;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withDefaults(): SecondEvent
    {
        return new SecondEvent(
            (string) 'SECOND'
        );
    }
}
