<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootWithAggregates;

final class ShoppingCart implements AggregateRoot
{
    use AggregateRootWithAggregates;

    private CartItems $cartItems;

    public static function initiate(ShoppingCartId $id): static
    {
        $shoppingCart = new static($id);
        $shoppingCart->recordThat(new ShoppingCartInitiated());

        return $shoppingCart;
    }

    public function add(ProductId $productId, int $currentPriceInCents): void
    {
        $this->cartItems->add($productId, $currentPriceInCents);
    }

    public function checkout(): void
    {
        if ($this->cartItems->isEmpty()) {
            throw SorryCantCheckout::becauseThereAreNoProductsInCart();
        }
        $this->recordThat(new CartCheckedOut());
    }

    protected function applyShoppingCartInitiated(ShoppingCartInitiated $event): void
    {
        $this->bootCartItemsAggregate();
    }

    private function bootCartItemsAggregate(): void
    {
        $this->cartItems = new CartItems($this->eventRecorder());
        $this->registerAggregate($this->cartItems);
    }
}
