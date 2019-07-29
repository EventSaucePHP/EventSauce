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

}
