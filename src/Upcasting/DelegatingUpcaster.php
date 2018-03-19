<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use EventSauce\EventSourcing\Header;
use Generator;

final class DelegatingUpcaster implements Upcaster
{
    /**
     * @var DelegatableUpcaster[]
     */
    private $upcasters;

    /**
     * @param DelegatableUpcaster[] ...$upcasters
     */
    public function __construct(DelegatableUpcaster ...$upcasters)
    {
        foreach ($upcasters as $upcaster) {
            $this->upcasters[$upcaster->type()][] = $upcaster;
        }
    }

    /**
     * @param array $message
     *
     * @return Upcaster|null
     */
    public function upcaster(array $message): ?Upcaster
    {
        $type = $message['headers'][Header::EVENT_TYPE];

        foreach ($this->upcasters[$type] ?? [] as $upcaster) {
            if ($upcaster->canUpcast($type, $message)) {
                return $upcaster;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function upcast(array $message): Generator
    {
        if ($upcaster = $this->upcaster($message)) {
            foreach ($upcaster->upcast($message) as $upcasted) {
                yield from $this->upcast($upcasted);
            }
        } else {
            yield $message;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canUpcast(string $type, array $message): bool
    {
        return isset($this->upcasters[$type]);
    }
}
