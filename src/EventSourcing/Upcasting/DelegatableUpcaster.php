<?php

namespace EventSauce\EventSourcing\Upcasting;

interface DelegatableUpcaster extends Upcaster
{
    public function type(): string;
}