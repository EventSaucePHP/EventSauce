<?php

namespace EventSauce\EventSourcing\Upcasting;

use Generator;

final class DelegatingUpcaster implements Upcaster
{
    /**
     * @var DelegatableUpcaster[][]
     */
    private $upcasters;

    /**
     * @var DelegatableUpcaster[]
     */
    private $lookupCache = [];

    public function __construct(DelegatableUpcaster ... $upcasters)
    {
        foreach ($upcasters as $upcaster) {
            $this->upcasters[$upcaster->type()][] = $upcaster;
        }
    }

    public function canUpcast(string $type, int $version): bool
    {
        foreach ($this->upcasters[$type] ?? [] as $upcaster) {
            if ($upcaster->canUpcast($type, $version)) {
                $this->lookupCache["{$type}-{$version}"] = $upcaster;
                return true;
            }
        }

        return false;
    }

    public function upcast(string $type, int $version, array $payload): Generator
    {
        $upcaster = $this->lookupCache["{$type}-{$version}"];

        yield from $upcaster->upcast($type, $version, $payload);
    }
}