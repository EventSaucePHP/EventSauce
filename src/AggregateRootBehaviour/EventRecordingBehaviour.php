<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AggregateRootBehaviour;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

trait EventRecordingBehaviour
{
    /**
     * @var Event[]
     */
    private $recordedEvents = [];

    protected function recordThat(object $event)
    {
        $this->apply($event);
        $this->recordedEvents[] = $event;
    }

    /**
     * @return object[]
     */
    public function releaseEvents(): array
    {
        $releasedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $releasedEvents;
    }

    abstract protected function apply(object $event);
}
