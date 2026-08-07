<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use Closure;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

/**
 * @extends AggregateRootTestCase<ShoppingCartId, ShoppingCart>
 */
class ShoppingCartTestCase extends AggregateRootTestCase
{
    protected function newAggregateRootId(): ShoppingCartId
    {
        return ShoppingCartId::create();
    }

    protected function aggregateRootClassName(): string
    {
        return ShoppingCart::class;
    }

    public function handle(Closure $closure): void
    {
        $aggregate = $this->repository->retrieve($this->aggregateRootId);
        $closure($aggregate);
        $this->repository->persist($aggregate);
    }
}
