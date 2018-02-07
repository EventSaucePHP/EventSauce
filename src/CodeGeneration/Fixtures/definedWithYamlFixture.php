<?php

namespace DefinedWith\Yaml;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Command;
use EventSauce\EventSourcing\Event;
use EventSauce\EventSourcing\PointInTime;

final class WeWentYamling implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $reference;

    /**
     * @var string
     */
    private $slogan;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        \Ramsey\Uuid\UuidInterface $reference,
        string $slogan
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->reference = $reference;
        $this->slogan = $slogan;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function reference(): \Ramsey\Uuid\UuidInterface
    {
        return $this->reference;
    }

    public function slogan(): string
    {
        return $this->slogan;
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
        return new WeWentYamling(
            $aggregateRootId,
            $timeOfRecording,
            \Ramsey\Uuid\Uuid::fromString($payload['reference']),
            (string) $payload['slogan']
        );
    }

    public function toPayload(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'slogan' => (string) $this->slogan
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withReference(\Ramsey\Uuid\UuidInterface $reference): WeWentYamling
    {
        $this->reference = $reference;

        return $this;
    }

    public static function withSlogan(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording, string $slogan): WeWentYamling
    {
        return new WeWentYamling(
            $aggregateRootId,
            $timeOfRecording,
            \Ramsey\Uuid\Uuid::fromString("c0b47bc5-2aaa-497b-83cb-11d97da03a95"),
            $slogan
        );
    }

}

final class VersionedEvent implements Event
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var PointInTime
     */
    private $timeOfRecording;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRecording,
        string $title
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRecording = $timeOfRecording;
        $this->title = $title;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function eventVersion(): int
    {
        return 2;
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
        return new VersionedEvent(
            $aggregateRootId,
            $timeOfRecording,
            (string) $payload['title']
        );
    }

    public function toPayload(): array
    {
        return [
            'title' => (string) $this->title
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withTitle(string $title): VersionedEvent
    {
        $this->title = $title;

        return $this;
    }

    public static function with(AggregateRootId $aggregateRootId, PointInTime $timeOfRecording): VersionedEvent
    {
        return new VersionedEvent(
            $aggregateRootId,
            $timeOfRecording,
            (string) 'Some Example Title'
        );
    }

}

final class HideFinancialDetailsOfFraudulentCompany implements Command
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
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $companyId;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRequest,
        \Ramsey\Uuid\UuidInterface $companyId
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRequest = $timeOfRequest;
        $this->companyId = $companyId;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function companyId(): \Ramsey\Uuid\UuidInterface
    {
        return $this->companyId;
    }

}

final class GoYamling implements Command
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
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $reference;

    /**
     * @var string
     */
    private $slogan;

    public function __construct(
        AggregateRootId $aggregateRootId,
        PointInTime $timeOfRequest,
        \Ramsey\Uuid\UuidInterface $reference,
        string $slogan
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->timeOfRequest = $timeOfRequest;
        $this->reference = $reference;
        $this->slogan = $slogan;
    }

    public function timeOfRequest(): PointInTime
    {
        return $this->timeOfRequest;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function reference(): \Ramsey\Uuid\UuidInterface
    {
        return $this->reference;
    }

    public function slogan(): string
    {
        return $this->slogan;
    }

}
