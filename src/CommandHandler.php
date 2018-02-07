<?php


namespace EventSauce\EventSourcing;

interface CommandHandler
{
    public function handle(Command $command);
}