<?php

namespace EventSauce\EventSourcing\Snapshotting\Tests;

class TurnLightOff
{
    /**
     * @var LightSwitchId
     */
    private $id;

    public function __construct(LightSwitchId $id)
    {
        $this->id = $id;
    }

    public function id(): LightSwitchId
    {
        return $this->id;
    }
}
