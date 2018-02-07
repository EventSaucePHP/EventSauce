<?php

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class UserSubscribedFromMailingList implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

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
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        string $username,
        string $mailingList
    ) {
        $this->aggregateRootId = $aggregateRootId;
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

    public function eventVersion(): int
    {
        return 1;
    }

    public function timeOfRecording(): PointInTime
    {
        return $this->timeOfRecording;
    }

    public static function fromPayload(
        array $payload,
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording): Event
    {
        return new UserSubscribedFromMailingList(
            $aggregateRootId,
            $timeOfRecording,
            (string) $payload['username'],
            (string) $payload['mailingList']
        );
    }

    public function toPayload(): array
    {
        return [
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList
        ];
    }

}

final class SubscribeToMailingList implements Command
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRequest,
        string $username,
        string $mailingList
    ) {
        $this->aggregateRootId = $aggregateRootId;
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

final class UnsubscribeFromMailingList implements Command
{
    /**
     * @var PointInTime
     */
    private $timeOfRequest;

    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

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
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRequest,
        string $username,
        string $mailingList,
        string $reason
    ) {
        $this->aggregateRootId = $aggregateRootId;
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
