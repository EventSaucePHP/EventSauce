<?php

namespace EventSauce\EventSourcing\Integration\Upcasting;

use EventSauce\EventSourcing\DotSeparatedSnakeCaseInflector;
use EventSauce\EventSourcing\Upcasting\DelegatableUpcaster;
use Generator;

class UpcasterStub implements DelegatableUpcaster
{
    public function canUpcast(string $type, int $version): bool
    {
        return $this->type() === $type && $version < 1;
    }

    public function upcast(string $type, int $version, array $payload): Generator
    {
        $payload['data']['property'] = 'upcasted';
        $payload['version'] = 1;

        yield $payload;
    }

    public function type(): string
    {
        return (new DotSeparatedSnakeCaseInflector())->classNameToType(UpcastedEventStub::class);
    }
}