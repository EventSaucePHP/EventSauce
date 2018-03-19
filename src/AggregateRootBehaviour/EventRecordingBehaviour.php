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

    /**
     * @param Event $event
     */
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

    /**
     * @param Event $event
     *
     * @return mixed
     */
    abstract protected function apply(Event $event);
}
