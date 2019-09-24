<?php

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\SnapshottingBehaviour;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;

class LightSwitch implements AggregateRootWithSnapshotting
{
    use AggregateRootBehaviour, SnapshottingBehaviour;

    const OFF = false;
    const ON = true;

    private $state = self::OFF;

    private function createSnapshotState()
    {
        return $this->state;
    }

    public function state(): bool
    {
        return $this->state;
    }

    public function turnOn(): void
    {
        if ($this->state == self::OFF) {
            $this->recordThat(LightSwitchWasFlipped::on());
        }
    }

    public function turnOff(): void
    {
        if ($this->state == self::ON) {
            $this->recordThat(LightSwitchWasFlipped::off());
        }
    }

    protected function applyLightSwitchWasFlipped(LightSwitchWasFlipped $event)
    {
        $this->state = $event->state();
    }

    static protected function reconstituteFromSnapshotState(AggregateRootId $id, bool $state): AggregateRootWithSnapshotting
    {
        $lightSwitch = new static($id);
        $lightSwitch->state = $state;

        return $lightSwitch;
    }
}
