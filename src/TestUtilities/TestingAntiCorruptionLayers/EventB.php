<?php

namespace EventSauce\EventSourcing\TestUtilities\TestingAntiCorruptionLayers;

class EventB
{
    public function __construct(public string $value = 'default')
    {
    }
}
