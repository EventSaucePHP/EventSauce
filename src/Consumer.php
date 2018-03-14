<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface Consumer
{
    public function handle(Message $message);
}
