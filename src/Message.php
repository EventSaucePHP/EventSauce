<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use DateTimeImmutable;
use RuntimeException;

/**
 * @template TPayload of object = object
 * @template TId of AggregateRootId = AggregateRootId
 *
 * @phpstan-type HeadersShape array<non-empty-string, int|string|array<mixed>|AggregateRootId|null|bool|float>
 */
final class Message
{
    public const TIME_OF_RECORDING_FORMAT = 'Y-m-d H:i:s.uO';

    /**
     * @param TPayload $payload
     * @param HeadersShape $headers
     */
    public function __construct(private object $payload, private array $headers = [])
    {
    }

    /**
     * @param non-empty-string $key
     * @param int|string|array<mixed>|AggregateRootId|null|bool|float $value
     *
     * @return Message<TPayload, TId>
     */
    public function withHeader(string $key, int|string|array|AggregateRootId|null|bool|float $value): Message
    {
        $clone = clone $this;
        $clone->headers[$key] = $value;

        return $clone;
    }

    /**
     * @param HeadersShape $headers
     *
     * @return Message<TPayload, TId>
     */
    public function withHeaders(array $headers): Message
    {
        $clone = clone $this;
        $clone->headers = $headers + $clone->headers;

        return $clone;
    }

    /**
     * @param non-empty-string $format
     *
     * @return Message<TPayload, TId>
     */
    public function withTimeOfRecording(
        DateTimeImmutable $timeOfRecording,
        string $format = self::TIME_OF_RECORDING_FORMAT
    ): Message {
        return $this->withHeaders([
            Header::TIME_OF_RECORDING => $timeOfRecording->format($format),
            Header::TIME_OF_RECORDING_FORMAT => $format,
        ]);
    }

    public function aggregateVersion(): int
    {
        $version = $this->headers[Header::AGGREGATE_ROOT_VERSION] ?? null;

        if ($version === null) {
            throw new RuntimeException("Can't get the version if the message has none.");
        }

        return (int) $version;
    }

    /**
     * @return TId|null
     */
    public function aggregateRootId(): ?AggregateRootId
    {
        return $this->headers[Header::AGGREGATE_ROOT_ID] ?? null;
    }

    public function aggregateRootType(): ?string
    {
        return $this->headers[Header::AGGREGATE_ROOT_TYPE] ?? null;
    }

    public function timeOfRecording(): DateTimeImmutable
    {
        $format = $this->headers[Header::TIME_OF_RECORDING_FORMAT] ?? self::TIME_OF_RECORDING_FORMAT;

        /* @var DateTimeImmutable */
        $timeOfRecording = DateTimeImmutable::createFromFormat(
            '!' . $format,
            $header = (string) ($this->headers[Header::TIME_OF_RECORDING] ?? '')
        );

        if ( ! $timeOfRecording instanceof DateTimeImmutable) {
            throw UnableToResolveTimeOfRecording::fromFormatAndHeader($format, $header);
        }

        return $timeOfRecording;
    }

    /**
     * @param non-empty-string $key
     *
     * @return int|string|array<mixed>|AggregateRootId|null|bool|float
     */
    public function header(string $key): int|string|array|AggregateRootId|null|bool|float
    {
        return $this->headers[$key] ?? null;
    }

    /**
     * @return HeadersShape
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @return TPayload
     */
    public function payload(): object
    {
        return $this->payload;
    }

    /**
     * @deprecated use ->payload instead
     * 
     * @return TPayload
     */
    public function event(): object
    {
        return $this->payload;
    }
}
