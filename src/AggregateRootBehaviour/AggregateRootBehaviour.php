<?php

namespace EventSauce\EventSourcing\AggregateRootBehaviour;

trait AggregateRootBehaviour
{
    use ConstructionBehaviour, EventApplyingBehaviour, EventRecordingBehaviour;
}