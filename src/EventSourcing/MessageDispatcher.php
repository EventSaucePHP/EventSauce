<?php

namespace EventSauce\EventSourcing;

interface MessageDispatcher
{
    public function dispatch(Message ... $messages);
}