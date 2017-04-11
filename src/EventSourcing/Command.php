<?php


namespace EventSauce\EventSourcing;

interface Command
{
    public function aggregateRootId(): AggregateRootId;
}