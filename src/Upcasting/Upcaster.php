<?php


namespace EventSauce\EventSourcing\Upcasting;

use Generator;

interface Upcaster
{
    public function canUpcast(string $type, int $version): bool;

    public function upcast(string $type, int $version, array $payload): Generator;
}