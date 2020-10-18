<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface EventSourcedAggregate
{
    public function apply(object $event): void;
}
