```php
<?php

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class UserSubscribedToMailingList implements SerializableEvent
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
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new UserSubscribedToMailingList(
            (string) $payload['username'],
            (string) $payload['mailingList']);
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
    public function withUsername(string $username): UserSubscribedToMailingList
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withMailingList(string $mailingList): UserSubscribedToMailingList
    {
        $this->mailingList = $mailingList;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): UserSubscribedToMailingList
    {
        return new UserSubscribedToMailingList(
            (string) 'example-user',
            (string) 'list-name'
        );
    }
}

final class UserUnsubscribedFromMailingList implements SerializableEvent
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
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new UserUnsubscribedFromMailingList(
            (string) $payload['username'],
            (string) $payload['mailingList'],
            (string) $payload['reason']);
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
    public function withUsername(string $username): UserUnsubscribedFromMailingList
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withMailingList(string $mailingList): UserUnsubscribedFromMailingList
    {
        $this->mailingList = $mailingList;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withReason(string $reason): UserUnsubscribedFromMailingList
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function with(): UserUnsubscribedFromMailingList
    {
        return new UserUnsubscribedFromMailingList(
            (string) 'example-user',
            (string) 'list-name',
            (string) 'no-longer-interested'
        );
    }
}

final class SubscribeToMailingList
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
}

final class UnsubscribeFromMailingList
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
}

```
