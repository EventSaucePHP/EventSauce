<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

class TurnLightOn
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
