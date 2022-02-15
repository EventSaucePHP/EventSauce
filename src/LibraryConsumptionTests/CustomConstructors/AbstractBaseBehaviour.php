<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\CustomConstructors;

use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;

trait AbstractBaseBehaviour
{
    use AggregateRootBehaviour;

    public function __construct()
    {
        parent::__construct();
    }

    private static function createNewInstance(AggregateRootId $aggregateRootId): static
    {
        $instance = new static();
        $instance->aggregateRootId = $aggregateRootId;

        return $instance;
    }
}
