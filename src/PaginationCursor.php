<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;
use Throwable;

use const JSON_THROW_ON_ERROR;

class PaginationCursor
{
    /**
     * @var array<string, int|string>
     */
    private array $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function intParam(string $key): ?int
    {
        $param = $this->parameters[$key] ?? null;

        return $param === null ? $param : (int) $param;
    }

    public function stringParam(string $key): ?string
    {
        $param = $this->parameters[$key] ?? null;

        return $param === null ? $param : (string) $param;
    }

    public function toString(): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($this->parameters)));
    }

    public static function fromString(string|null $cursor): static|null
    {
        if ($cursor === null) {
            return null;
        }

        try {
            $parameters = json_decode(
                base64_decode(str_replace(['-', '_'], ['+', '/'], $cursor)),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (Throwable $throwable) {
            throw new RuntimeException('Unable to decode cursor, error: ' . $throwable->getMessage(), 0, $throwable);
        }

        return new self($parameters);
    }
}
