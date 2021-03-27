<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use Generator;

class UpcasterStub implements Upcaster
{
    public function upcast(array $message): Generator
    {
        $message['payload']['property'] = 'upcasted';
        $message['headers']['version'] = 1;

        yield $message;
    }
}
