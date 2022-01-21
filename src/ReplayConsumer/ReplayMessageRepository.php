<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\ReplayConsumer;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\UnableToRetrieveMessages;
use Generator;

interface ReplayMessageRepository
{
    /**
     * @return generator<Message>
     *                            Return of the generator is the offset to use for the next page
     *
     * @throws UnableToRetrieveMessages
     */
    public function retrieveForReplayFromOffset(int $offset = 0, int $pageSize = 1000): Generator;

    public function hasMessagesAfterOffset(int $offset): bool;
}
