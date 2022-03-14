<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities;

class ExpectedEvent
{
    /**
     * @var callable|null
     */
    private $callable;

    public function __construct(protected ?string $expectedClass, ?callable $callable = null)
    {
        $this->callable = $callable;
    }

    public static function matches(callable $callable): self
    {
        return new self(null, $callable);
    }

    public static function ofType(string $class): self
    {
        return new self($class);
    }

    public function toMatch(callable $callable): self
    {
        return new self(
            $this->expectedClass,
            $callable
        );
    }

    public function assertEquals(object $recordedEvent): bool
    {
        $call = $this->callable;
        $callbackResult = $call !== null ? $call($recordedEvent) : null;

        $classMatchResult = ($this->expectedClass !== null) ? ($recordedEvent instanceof $this->expectedClass) : true;

        return $classMatchResult && $callbackResult !== false;
    }
}
