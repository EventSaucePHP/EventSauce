<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

interface Upcaster
{
    public function upcast(array $message): array;
}
