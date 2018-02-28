<?php


namespace EventSauce\EventSourcing;

interface AggregateRootId
{
    /**
     * @param mixed $otherId
     * @return bool
     */
    public function equals($otherId): bool;

    /**
     * @return string
     */
    public function toString(): string;

    /**
     * @param string $aggregateRootId
     * @return static
     */
    public static function fromString(string $aggregateRootId): AggregateRootId;
}