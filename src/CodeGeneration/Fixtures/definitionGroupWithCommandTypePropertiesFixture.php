<?php

declare(strict_types=1);

namespace With\Commands;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class DoSomething implements SerializablePayload
{
    public function __construct(
        private string $reason
    ) {
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public static function fromPayload(array $payload): self
    {
        return new DoSomething(
            (string) $payload['reason']
        );
    }

    public function toPayload(): array
    {
        return [
            'reason' => (string) $this->reason,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withReason(string $reason): DoSomething
    {
        $clone = clone $this;
        $clone->reason = $reason;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withDefaults(): DoSomething
    {
        return new DoSomething(
            (string) 'Because reasons.'
        );
    }
}
