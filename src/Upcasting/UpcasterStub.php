<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

/**
 * @testAsset
 */
class UpcasterStub implements Upcaster
{
    public function upcast(array $message): array
    {
        $message['payload']['property'] = 'upcasted';
        $message['headers']['version'] = 1;

        return $message;
    }
}
