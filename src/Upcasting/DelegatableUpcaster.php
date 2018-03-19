<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

interface DelegatableUpcaster extends Upcaster
{
    /**
     * @return string
     */
    public function type(): string;
}
