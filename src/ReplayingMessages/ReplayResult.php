<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayingMessages;

use EventSauce\EventSourcing\PaginationCursor;

class ReplayResult
{
    public function __construct(private int $messagesHandled, private PaginationCursor $cursor)
    {
    }

    public function messagesHandled(): int
    {
        return $this->messagesHandled;
    }

    public function cursor(): PaginationCursor
    {
        return $this->cursor;
    }
}
