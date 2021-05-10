<?php

namespace EventSauce\EventSourcing\UuidMessageDecorator;

interface UuidGenerator
{
    public function generate(): string;
}
