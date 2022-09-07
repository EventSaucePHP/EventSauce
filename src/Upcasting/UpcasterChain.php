<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

class UpcasterChain implements Upcaster
{
    /**
     * @var Upcaster[]
     */
    private array $upcasters;

    public function __construct(Upcaster ...$upcasters)
    {
        $this->upcasters = $upcasters;
    }

    public function upcast(array $message): array
    {
        foreach ($this->upcasters as $upcaster) {
            $message = $upcaster->upcast($message);
        }

        return $message;
    }
}
