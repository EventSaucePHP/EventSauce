<?php

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\AggregateRootId;
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

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        PointInTime $timeOfRecording,
        string $username,
        string $mailingList
    ) {
        $this->timeOfRecording = $timeOfRecording;
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        PointInTime $timeOfRecording): Event
    {
        return new UserSubscribedFromMailingList(
            $timeOfRecording,
            (string) $payload['username'],
            (string) $payload['mailingList']
        );
    }

    public function toPayload(): array
    {
        return [
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList,
            '__event_version' => 1,
        ];
    }

}

final class SubscribeToMailingList
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        PointInTime $timeOfRequest,
        string $username,
        string $mailingList
    ) {
        $this->timeOfRequest = $timeOfRequest;
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
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
     * @var PointInTime
     */
    private $timeOfRequest;

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
        PointInTime $timeOfRequest,
        string $username,
        string $mailingList,
        string $reason
    ) {
        $this->timeOfRequest = $timeOfRequest;
        $this->username = $username;
        $this->mailingList = $mailingList;
        $this->reason = $reason;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
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
