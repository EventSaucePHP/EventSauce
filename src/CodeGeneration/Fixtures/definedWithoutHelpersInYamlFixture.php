<?php

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class UserSubscribedFromMailingList implements Event
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

    public static function fromPayload(array $payload): Event
    {
        return new UserSubscribedFromMailingList(
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
