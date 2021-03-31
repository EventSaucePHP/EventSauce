<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use DateTimeImmutable;
use RuntimeException;

final class Message
{
    public const TIME_OF_RECORDING_FORMAT = 'Y-m-d H:i:s.uO';

    public function __construct(private object $event, private array $headers = [])
    {
    }

    public function withHeader(string $key, int|string|null|AggregateRootId $value): Message
    {
        $clone = clone $this;
        $clone->headers[$key] = $value;

        return $clone;
    }

    public function withHeaders(array $headers): Message
    {
        $clone = clone $this;
        $clone->headers = $headers + $clone->headers;

        return $clone;
    }

    public function withTimeOfRecording(DateTimeImmutable $timeOfRecording): Message
    {
        return $this->withHeader(Header::TIME_OF_RECORDING, $timeOfRecording->format(self::TIME_OF_RECORDING_FORMAT));
    }

    public function aggregateVersion(): int
    {
        $version = $this->headers[Header::AGGREGATE_ROOT_VERSION] ?? null;

        if (null === $version) {
            throw new RuntimeException("Can't get the version if the message has none.");
        }

        return (int) $version;
    }

    public function aggregateRootId(): ?AggregateRootId
    {
        return $this->headers[Header::AGGREGATE_ROOT_ID] ?? null;
    }

    public function timeOfRecording(): DateTimeImmutable
    {
        /* @var DateTimeImmutable */
        $timeOfRecording = DateTimeImmutable::createFromFormat(
            self::TIME_OF_RECORDING_FORMAT,
            $header = ($this->headers[Header::TIME_OF_RECORDING] ?? '')
        );

        if ( ! $timeOfRecording instanceof DateTimeImmutable) {
            throw UnableToDetermineTimeOfRecording::fromFormatAndHeader(self::TIME_OF_RECORDING_FORMAT, $header);
        }

        return $timeOfRecording;
    }

    public function header(string $key): int|string|array|AggregateRootId|null
    {
        return $this->headers[$key] ?? null;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function event(): object
    {
        return $this->event;
    }
}
