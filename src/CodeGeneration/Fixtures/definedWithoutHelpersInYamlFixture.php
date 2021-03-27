<?php

declare(strict_types=1);

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class UserSubscribedFromMailingList implements SerializablePayload
{
    public function __construct(
        private string $username,
        private string $mailingList
    ) {
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public static function fromPayload(array $payload): self
    {
        return new UserSubscribedFromMailingList(
            (string) $payload['username'],
            (string) $payload['mailingList']
        );
    }

    public function toPayload(): array
    {
        return [
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList,
        ];
    }
}

final class SubscribeToMailingList implements SerializablePayload
{
    public function __construct(
        private string $username,
        private string $mailingList
    ) {
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public static function fromPayload(array $payload): self
    {
        return new SubscribeToMailingList(
            (string) $payload['username'],
            (string) $payload['mailingList']
        );
    }

    public function toPayload(): array
    {
        return [
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList,
        ];
    }
}

final class UnsubscribeFromMailingList implements SerializablePayload
{
    public function __construct(
        private string $username,
        private string $mailingList,
        private string $reason
    ) {
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public static function fromPayload(array $payload): self
    {
        return new UnsubscribeFromMailingList(
            (string) $payload['username'],
            (string) $payload['mailingList'],
            (string) $payload['reason']
        );
    }

    public function toPayload(): array
    {
        return [
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList,
            'reason' => (string) $this->reason,
        ];
    }
}
