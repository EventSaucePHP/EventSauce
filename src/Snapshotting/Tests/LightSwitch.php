<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Snapshotting\AggregateRootWithSnapshotting;
use EventSauce\EventSourcing\Snapshotting\SnapshottingBehaviour;

final class LightSwitch implements AggregateRootWithSnapshotting
{
    use AggregateRootBehaviour;
    use SnapshottingBehaviour;

    public const OFF = false;
    public const ON = true;

    private bool $state = self::OFF;

    private function createSnapshotState(): bool
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

    protected function applyLightSwitchWasFlipped(LightSwitchWasFlipped $event): void
    {
        $this->state = $event->state();
    }

    protected static function reconstituteFromSnapshotState(AggregateRootId $id, mixed $state): AggregateRootWithSnapshotting
    {
        $lightSwitch = new static($id);
        $lightSwitch->state = (bool) $state;

        return $lightSwitch;
    }
}
