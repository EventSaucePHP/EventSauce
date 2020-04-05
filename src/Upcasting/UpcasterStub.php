<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use Generator;

class UpcasterStub implements Upcaster
{
    public function upcast(array $payload): Generator
    {
        $payload['payload']['property'] = 'upcasted';
        $payload['headers']['version'] = 1;

        yield $payload;
    }
}
