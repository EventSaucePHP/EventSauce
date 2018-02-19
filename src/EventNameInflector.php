<?php

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Event;

interface EventNameInflector
{
    public function classNameToEventName(string $className): string;
    public function eventNameToClassName(string $eventName): string;
    public function eventToEventName(Event $evet): string;
}