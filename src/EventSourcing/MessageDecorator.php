<?php

namespace EventSauce\EventSourcing;

interface MessageDecorator
{
    public function decorate(Message $message): Message;
}