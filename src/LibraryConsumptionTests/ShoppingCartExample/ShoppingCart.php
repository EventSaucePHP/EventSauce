<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootWithAggregates;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events\CartCheckedOut;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events\ShoppingCartInitiated;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Exceptions\SorryCantCheckout;

class ShoppingCart implements AggregateRoot
{
    use AggregateRootWithAggregates;

    private CartItems $cartItems;

    public static function initiate(ShoppingCartId $id): static
    {
        $shoppingCart = new static($id);
        $shoppingCart->recordThat(new ShoppingCartInitiated());

        return $shoppingCart;
    }

    public function addProduct(ProductId $productId, int $currentPriceInCents): void
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
