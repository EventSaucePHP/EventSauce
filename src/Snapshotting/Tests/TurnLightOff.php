<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

class TurnLightOff
{
    public function __construct(private LightSwitchId $id)
    {
    }

    public function id(): LightSwitchId
    {
        return $this->id;
    }
}
