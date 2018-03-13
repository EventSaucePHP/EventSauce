<?php

namespace With\Commands;

use EventSauce\EventSourcing\Event;

final class DoSomething
{
    /**
     * @var string
     */
    private $reason;

    public function __construct(
        string $reason
    ) {
        $this->reason = $reason;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
