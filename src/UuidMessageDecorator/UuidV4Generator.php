<?php

namespace EventSauce\EventSourcing\UuidMessageDecorator;

use Ramsey\Uuid\Uuid;

class UuidV4Generator implements UuidGenerator
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
