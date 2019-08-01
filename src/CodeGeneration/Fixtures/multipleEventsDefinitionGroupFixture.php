<?php

declare(strict_types=1);

namespace Multiple\Events\DefinitionGroup;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class FirstEvent implements SerializablePayload
{
    /**
     * @var string
     */
    private $firstField;

    public function __construct(
        string $firstField
    ) {
        $this->firstField = $firstField;
    }

    public function firstField(): string
    {
        return $this->firstField;
    }

    public static function fromPayload(array $payload): SerializablePayload
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
    public static function with(): FirstEvent
    {
        return new FirstEvent(
            (string) 'FIRST'
        );
    }
}

final class SecondEvent implements SerializablePayload
{
    /**
     * @var string
     */
    private $secondField;

    public function __construct(
        string $secondField
    ) {
        $this->secondField = $secondField;
    }

    public function secondField(): string
    {
        return $this->secondField;
    }

    public static function fromPayload(array $payload): SerializablePayload
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
    public static function with(): SecondEvent
    {
        return new SecondEvent(
            (string) 'SECOND'
        );
    }
}
