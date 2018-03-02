<?php


namespace EventSauce\EventSourcing\Upcasting;

use Generator;

interface Upcaster
{
    public function canUpcast(string $type, array $payload): bool;

    public function upcast(array $payload): Generator;
}