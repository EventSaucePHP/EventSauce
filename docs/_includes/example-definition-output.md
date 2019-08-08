```php
<?php

declare(strict_types=1);

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class UserSubscribedToMailingList implements SerializablePayload
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        \Ramsey\Uuid\UuidInterface $id,
        string $username,
        string $mailingList
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function id(): \Ramsey\Uuid\UuidInterface
    {
        return $this->id;
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
        return new UserSubscribedToMailingList(
            \Ramsey\Uuid\Uuid::fromString($payload['id']),
            (string) $payload['username'],
            (string) $payload['mailingList']
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withUsername(string $username): UserSubscribedToMailingList
    {
        $clone = clone $this;
        $clone->username = $username;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withMailingList(string $mailingList): UserSubscribedToMailingList
    {
        $clone = clone $this;
        $clone->mailingList = $mailingList;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withId(\Ramsey\Uuid\UuidInterface $id): UserSubscribedToMailingList
    {
        return new UserSubscribedToMailingList(
            $id,
            (string) 'example-user',
            (string) 'list-name'
        );
    }
}

final class UserUnsubscribedFromMailingList implements SerializablePayload
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;

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
        \Ramsey\Uuid\UuidInterface $id,
        string $username,
        string $mailingList,
        string $reason
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->mailingList = $mailingList;
        $this->reason = $reason;
    }

    public function id(): \Ramsey\Uuid\UuidInterface
    {
        return $this->id;
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
        return new UserUnsubscribedFromMailingList(
            \Ramsey\Uuid\Uuid::fromString($payload['id']),
            (string) $payload['username'],
            (string) $payload['mailingList'],
            (string) $payload['reason']
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList,
            'reason' => (string) $this->reason,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withUsername(string $username): UserUnsubscribedFromMailingList
    {
        $clone = clone $this;
        $clone->username = $username;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withMailingList(string $mailingList): UserUnsubscribedFromMailingList
    {
        $clone = clone $this;
        $clone->mailingList = $mailingList;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withReason(string $reason): UserUnsubscribedFromMailingList
    {
        $clone = clone $this;
        $clone->reason = $reason;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withId(\Ramsey\Uuid\UuidInterface $id): UserUnsubscribedFromMailingList
    {
        return new UserUnsubscribedFromMailingList(
            $id,
            (string) 'example-user',
            (string) 'list-name',
            (string) 'no-longer-interested'
        );
    }
}

final class SubscribeToMailingList implements SerializablePayload
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        \Ramsey\Uuid\UuidInterface $id,
        string $username,
        string $mailingList
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function id(): \Ramsey\Uuid\UuidInterface
    {
        return $this->id;
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
            \Ramsey\Uuid\Uuid::fromString($payload['id']),
            (string) $payload['username'],
            (string) $payload['mailingList']
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
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
    public static function withId(\Ramsey\Uuid\UuidInterface $id): SubscribeToMailingList
    {
        return new SubscribeToMailingList(
            $id,
            (string) 'example-user',
            (string) 'list-name'
        );
    }
}

final class UnsubscribeFromMailingList implements SerializablePayload
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;

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
        \Ramsey\Uuid\UuidInterface $id,
        string $username,
        string $mailingList,
        string $reason
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->mailingList = $mailingList;
        $this->reason = $reason;
    }

    public function id(): \Ramsey\Uuid\UuidInterface
    {
        return $this->id;
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
            \Ramsey\Uuid\Uuid::fromString($payload['id']),
            (string) $payload['username'],
            (string) $payload['mailingList'],
            (string) $payload['reason']
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
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
    public static function withId(\Ramsey\Uuid\UuidInterface $id): UnsubscribeFromMailingList
    {
        return new UnsubscribeFromMailingList(
            $id,
            (string) 'example-user',
            (string) 'list-name',
            (string) 'no-longer-interested'
        );
    }
}
```
