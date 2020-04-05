<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use Generator;

class UpcasterChain implements Upcaster
{
    /**
     * @var Upcaster[]
     */
    private $upcasters;

    public function __construct(Upcaster ...$upcasters)
    {
        $this->upcasters = $upcasters;
    }

    public function upcast(array $message): Generator
    {
        $messages = [$message];

        foreach ($this->upcasters as $upcaster) {
            $messages = $this->invokeUpcaster($messages, $upcaster);
        }

        yield from $messages;
    }

    private function invokeUpcaster(iterable $messages, Upcaster $upcaster): Generator
    {
        foreach ($messages as $message) {
            yield from $upcaster->upcast($message);
        }
    }
}
