<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\PaginationCursor;

class ProjectionHandler
{
    public function __construct(
        private MessageRepository $repository,
        private MessageConsumer $consumer,
        private ProjectionStatusRepository $projectionStatusRepository,
    ) {
    }

    public function handle(ProjectionId $projectionId, PaginationCursor $initialCursor): void
    {
        $cursor = $this->projectionStatusRepository->getCursorAndLock($projectionId) ?? $initialCursor;

        $messages = $this->repository->paginate($cursor);

        /** @var Message $message */
        foreach ($messages as $message) {
            $this->consumer->handle($message);
        }

        $this->projectionStatusRepository->persistCursorAndRelease($projectionId, $messages->getReturn());
    }
}
