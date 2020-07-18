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

    const OFF = false;
    const ON = true;

    /**
     * @var bool
     */
    private $state = self::OFF;

    /**
     * @return bool
     */
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
        if (self::OFF == $this->state) {
            $this->recordThat(LightSwitchWasFlipped::on());
        }
    }

    public function turnOff(): void
    {
        if (self::ON == $this->state) {
            $this->recordThat(LightSwitchWasFlipped::off());
        }
    }

    protected function applyLightSwitchWasFlipped(LightSwitchWasFlipped $event): void
    {
        $this->state = $event->state();
    }

    /**
     * @param bool $state
     */
    protected static function reconstituteFromSnapshotState(AggregateRootId $id, $state): AggregateRootWithSnapshotting
    {
        $lightSwitch = new static($id);
        $lightSwitch->state = (bool) $state;

        return $lightSwitch;
    }
}
