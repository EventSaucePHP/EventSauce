<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\UuidMessageDecorator;

interface UuidGenerator
{
    public function generate(): string;
}
