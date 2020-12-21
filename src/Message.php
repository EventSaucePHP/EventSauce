<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use DateTimeImmutable;
use RuntimeException;

final class Message
{
    private const TIME_OF_RECORDING_FORMAT = 'Y-m-d H:i:s.uO';

    /**
     * @var object
     */
    private $event;

    /**
     * @var array
     */
    private $headers;

    public function __construct(object $event, array $headers = [])
    {
        $this->event = $event;
        $this->headers = $headers;
    }

    /**
     * @param mixed $value
     */
    public function withHeader(string $key, $value): Message
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
        /** @var DateTimeImmutable */
        return DateTimeImmutable::createFromFormat(
            self::TIME_OF_RECORDING_FORMAT,
            $this->headers[Header::TIME_OF_RECORDING]
        );
    }

    /**
     * @return mixed|null
     */
    public function header(string $key)
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
