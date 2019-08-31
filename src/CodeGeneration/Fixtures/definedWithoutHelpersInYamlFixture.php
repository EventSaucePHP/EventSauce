<?php

declare(strict_types=1);

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class UserSubscribedFromMailingList implements SerializablePayload
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        string $username,
        string $mailingList
    ) {
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public static function fromPayload(array $payload): SerializablePayload
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

    /**
     * @codeCoverageIgnore
     */
    public function withUsername(string $username): UserSubscribedFromMailingList
    {
        $clone = clone $this;
        $clone->username = $username;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withMailingList(string $mailingList): UserSubscribedFromMailingList
    {
        $clone = clone $this;
        $clone->mailingList = $mailingList;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): UserSubscribedFromMailingList
    {
        return new UserSubscribedFromMailingList(
            (string) 'example-user',
            (string) 'list-name'
        );
    }
}

final class SubscribeToMailingList implements SerializablePayload
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        string $username,
        string $mailingList
    ) {
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public static function fromPayload(array $payload): SerializablePayload
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

    /**
     * @codeCoverageIgnore
     */
    public function withUsername(string $username): SubscribeToMailingList
    {
        $clone = clone $this;
        $clone->username = $username;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withMailingList(string $mailingList): SubscribeToMailingList
    {
        $clone = clone $this;
        $clone->mailingList = $mailingList;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): SubscribeToMailingList
    {
        return new SubscribeToMailingList(
            (string) 'example-user',
            (string) 'list-name'
        );
    }
}

final class UnsubscribeFromMailingList implements SerializablePayload
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    /**
     * @var string
     */
    private $reason;

    public function __construct(
        string $username,
        string $mailingList,
        string $reason
    ) {
        $this->username = $username;
        $this->mailingList = $mailingList;
        $this->reason = $reason;
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

    public static function fromPayload(array $payload): SerializablePayload
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

    /**
     * @codeCoverageIgnore
     */
    public function withUsername(string $username): UnsubscribeFromMailingList
    {
        $clone = clone $this;
        $clone->username = $username;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withMailingList(string $mailingList): UnsubscribeFromMailingList
    {
        $clone = clone $this;
        $clone->mailingList = $mailingList;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withReason(string $reason): UnsubscribeFromMailingList
    {
        $clone = clone $this;
        $clone->reason = $reason;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): UnsubscribeFromMailingList
    {
        return new UnsubscribeFromMailingList(
            (string) 'example-user',
            (string) 'list-name',
            (string) 'no-longer-interested'
        );
    }
}
