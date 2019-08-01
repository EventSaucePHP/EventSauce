<?php

declare(strict_types=1);

namespace With\Commands;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class DoSomething implements SerializablePayload
{
    /**
     * @var string
     */
    private $reason;

    public function __construct(
        string $reason
    ) {
        $this->reason = $reason;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public static function fromPayload(array $payload): SerializablePayload
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
    public static function with(): DoSomething
    {
        return new DoSomething(
            (string) 'Because reasons.'
        );
    }
}
