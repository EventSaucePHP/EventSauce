<?php

namespace EventSauce\EventSourcing;

interface Consumer
{
    public function handle(Message $message);
}