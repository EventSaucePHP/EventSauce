<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use Generator;

interface Upcaster
{
    /**
     * @param string $type
     * @param array  $message
     *
     * @return bool
     */
    public function canUpcast(string $type, array $message): bool;

    /**
     * @param array $message
     *
     * @return Generator
     */
    public function upcast(array $message): Generator;
}
