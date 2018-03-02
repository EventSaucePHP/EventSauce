<?php

namespace EventSauce\EventSourcing\Upcasting;

use EventSauce\EventSourcing\Header;
use Generator;

final class DelegatingUpcaster implements Upcaster
{
    /**
     * @var DelegatableUpcaster[][]
     */
    private $upcasters;

    public function __construct(DelegatableUpcaster ... $upcasters)
    {
        foreach ($upcasters as $upcaster) {
            $this->upcasters[$upcaster->type()][] = $upcaster;
        }
    }

    public function upcaster(array $payload): ?Upcaster
    {
        $type = $payload['headers'][Header::EVENT_TYPE];

        foreach ($this->upcasters[$type] ?? [] as $upcaster) {
            if ($upcaster->canUpcast($type, $payload)) {
                return $upcaster;
            }
        }

        return null;
    }

    public function upcast(array $payload): Generator
    {
        if ($upcaster = $this->upcaster($payload)) {
            foreach ($upcaster->upcast($payload) as $upcasted) {
                yield from $this->upcast($upcasted);
            }
        } else {
            yield $payload;
        }
    }

    public function canUpcast(string $type, array $payload): bool
    {
        return isset($this->upcasters[$type]);
    }
}