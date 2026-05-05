<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use DateTimeImmutable;
use RuntimeException;

/**
 * @template TId of AggregateRootId = AggregateRootId
 * @template TPayload of object = object
 *
 * @phpstan-type HeaderValue int|string|array<mixed>|TId|null|bool|float
 * @phpstan-type HeadersShape array<non-empty-string, HeaderValue>
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
     * @param HeaderValue $value
     *
     * @return Message<TId, TPayload>
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
     * @return Message<TId, TPayload>
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
     * @return Message<TId, TPayload>
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
        \assert($version === null || \is_numeric($version));

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
        $aggregateRootId = $this->headers[Header::AGGREGATE_ROOT_ID] ?? null;
        \assert($aggregateRootId === null || $aggregateRootId instanceof AggregateRootId);

        return $aggregateRootId;
    }

    public function aggregateRootType(): ?string
    {
        $aggregateRootType = $this->headers[Header::AGGREGATE_ROOT_TYPE] ?? null;
        \assert($aggregateRootType === null || \is_string($aggregateRootType));

        return $aggregateRootType;
    }

    public function timeOfRecording(): DateTimeImmutable
    {
        $format = $this->headers[Header::TIME_OF_RECORDING_FORMAT] ?? self::TIME_OF_RECORDING_FORMAT;
        $header = $this->headers[Header::TIME_OF_RECORDING] ?? '';
        \assert(\is_string($format));
        \assert(\is_string($header));

        $timeOfRecording = \DateTimeImmutable::createFromFormat('!' . $format, $header);

        if ( ! $timeOfRecording instanceof DateTimeImmutable) {
            throw UnableToResolveTimeOfRecording::fromFormatAndHeader($format, $header);
        }

        return $timeOfRecording;
    }

    /**
     * @param non-empty-string $key
     *
     * @return HeaderValue
     */
    public function header(string $key): int|string|array|AggregateRootId|bool|float|null
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
