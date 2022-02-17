<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AntiCorruptionLayer\MessageFilters;

use EventSauce\EventSourcing\Message;

class MatchAnyMessageFilter implements MessageFilter
{
    /** @var MessageFilter[] */
    private array $filters;

    public function __construct(MessageFilter ... $filters)
    {
        $this->filters = $filters;
    }

    public function allows(Message $message): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->allows($message)) {
                return true;
            }
        }

        return false;
    }
}
