<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AggregateRootBehaviour;

trait AggregateRootBehaviour
{
    use ConstructionBehaviour, EventApplyingBehaviour, EventRecordingBehaviour;
}
