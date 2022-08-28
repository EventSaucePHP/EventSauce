<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer;

use EventSauce\EventSourcing\Message;

class MatchAllMessageFilters implements MessageFilter
{
    /** @var MessageFilter[] */
    private array $filters;

    public function __construct(MessageFilter ...$filters)
    {
        $this->filters = $filters;
    }

    public function allows(Message $message): bool
    {
        foreach ($this->filters as $filter) {
            if ( ! $filter->allows($message)) {
                return false;
            }
        }

        return true;
    }
}
