<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

class EventRecorder
{
    /**
     * @var callable
     */
    private $recorder;

    public function __construct(callable $recorder)
    {
        $this->recorder = $recorder;
    }

    public function recordThat(object $event): void
    {
        call_user_func($this->recorder, $event);
    }
}
