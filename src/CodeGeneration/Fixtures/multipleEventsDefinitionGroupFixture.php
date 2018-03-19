<?php

namespace Multiple\Events\DefinitionGroup;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class FirstEvent implements SerializableEvent
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
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new FirstEvent(
            (string) $payload['firstField']);
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
        $this->firstField = $firstField;

        return $this;
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

final class SecondEvent implements SerializableEvent
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
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new SecondEvent(
            (string) $payload['secondField']);
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
        $this->secondField = $secondField;

        return $this;
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
