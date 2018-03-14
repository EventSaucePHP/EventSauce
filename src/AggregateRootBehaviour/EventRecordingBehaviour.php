<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AggregateRootBehaviour;

use EventSauce\EventSourcing\Event;

trait EventRecordingBehaviour
{
    /**
     * @var Event[]
     */
    private $recordedEvents = [];

    protected function recordThat(Event $event)
    {
        $this->apply($event);
        $this->recordedEvents[] = $event;
    }

    /**
     * @return Event[]
     */
    public function releaseEvents(): array
    {
        $releasedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $releasedEvents;
    }

    abstract protected function apply(Event $event);
}
