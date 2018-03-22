<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\AggregateRootBehaviour\AggregateRootBehaviour;

abstract class BaseAggregateRoot implements ReconstitutableAggregateRoot
{
    use AggregateRootBehaviour;
}
