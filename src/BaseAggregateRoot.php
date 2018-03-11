<?php

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\AggregateRootBehaviour\AggregateRootBehaviour;

abstract class BaseAggregateRoot implements AggregateRoot
{
    use AggregateRootBehaviour;
}