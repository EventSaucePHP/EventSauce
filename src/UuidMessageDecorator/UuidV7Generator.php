<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\UuidMessageDecorator;

use Ramsey\Uuid\Uuid;

class UuidV7Generator implements UuidGenerator
{
    public function generate(): string
    {
        return Uuid::uuid7()->toString();
    }
}
