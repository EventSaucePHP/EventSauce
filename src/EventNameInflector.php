<?php

namespace EventSauce\EventSourcing;

interface EventNameInflector
{
    public function classNameToEventName(string $className): string;
    public function eventNameToClassName(string $eventName): string;
    public function eventToEventName(Event $event): string;
}