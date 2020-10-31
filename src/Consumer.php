<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface Consumer
{
    /**
     * @return void
     */
    public function handle(Message $message);
}
