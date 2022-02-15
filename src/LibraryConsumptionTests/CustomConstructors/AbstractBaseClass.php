<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\CustomConstructors;

abstract class AbstractBaseClass
{
    /**
     * @var array|string[]
     */
    private array $values;

    public function __construct(array $values = ['value'])
    {
        $this->values = $values;
    }

    public function values(): array
    {
        return $this->values;
    }
}
