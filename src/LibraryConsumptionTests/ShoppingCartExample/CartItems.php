<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\AggregateAppliesKnownEvents;
use EventSauce\EventSourcing\EventRecorder;
use EventSauce\EventSourcing\EventSourcedAggregate;

class CartItems implements EventSourcedAggregate
{
    use AggregateAppliesKnownEvents;

    private array $items = [];

    public function __construct(private EventRecorder $eventRecorder)
    {
    }

    public function add(ProductId $productId, int $currentPriceInCents): void
    {
        if (array_key_exists($productId->toString(), $this->items)) {
            if ($this->items[$productId->toString()]['price'] !== $currentPriceInCents) {
                throw SorryCantAddProduct::becauseThePriceHasChanged();
            }
        }
        $this->eventRecorder->recordThat(new ProductAddedToCart($productId, $currentPriceInCents));
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    protected function applyProductAddedToCart(ProductAddedToCart $event): void
    {
        $productIdAsString = $event->productId->toString();
        if ( ! array_key_exists($productIdAsString, $this->items)) {
            $this->items[$productIdAsString] = ['qty' => 0, 'price' => $event->price];
        }
        ++$this->items[$productIdAsString]['qty'];
    }
}
