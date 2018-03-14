<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

interface DelegatableUpcaster extends Upcaster
{
    public function type(): string;
}
