<?php

namespace EventSauce\EventSourcing\TestUtilities\TestingAntiCorruptionLayers;

class EventA
{
    public function __construct(public $value = 'default')
    {
    }
}
