<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events\CartCheckedOut;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events\ProductAddedToCart;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events\ShoppingCartInitiated;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Exceptions\SorryCantCheckout;

class ShoppingCartLifecycleTest extends ShoppingCartTestCase
{
    /** @test */
    public function a_shopping_cart_can_be_initiated(): void
    {
        $cart = ShoppingCart::initiate($this->aggregateRootId());
        $this->repository->persist($cart);

        $this->then(
            new ShoppingCartInitiated()
        );
    }

    /** @test */
    public function shopping_cart_cannot_be_checked_out_when_there_are_no_items_in_it(): void
    {
        $this
            ->given(new ShoppingCartInitiated())
            ->when(function (ShoppingCart $cart): void {
                $cart->checkout();
            })
            ->expectToFail(SorryCantCheckout::becauseThereAreNoProductsInCart());
    }

    /** @test */
    public function a_shopping_cart_can_checkout_when_there_are_products_in_the_cart(): void
    {
        $this
            ->given(
                new ShoppingCartInitiated(),
                new ProductAddedToCart(ProductId::fromString('garlic-sauce'), 250),
                new ProductAddedToCart(ProductId::fromString('garlic-sauce'), 250)
            )
            ->when(function (ShoppingCart $cart): void {
                $cart->checkout();
            })
            ->then(
                new CartCheckedOut()
            );
    }
}
