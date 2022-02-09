<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events\ProductAddedToCart;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events\ShoppingCartInitiated;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Exceptions\SorryCantAddProduct;

class CartItemsTest extends ShoppingCartTestCase
{
    /** @test */
    public function a_item_can_be_added_to_a_cart(): void
    {
        $this
            ->given(
                new ShoppingCartInitiated(),
            )
            ->when(function (ShoppingCart $cart): void {
                $cart->addProduct(new ProductId('garlic sauce'), 250);
            })
            ->then(
                new ProductAddedToCart(new ProductId('garlic sauce'), 250)
            );
    }

    /** @test */
    public function same_item_can_be_added_multiple_times(): void
    {
        $this
            ->given(
                new ShoppingCartInitiated(),
                new ProductAddedToCart(new ProductId('garlic sauce'), 250),
            )
            ->when(function (ShoppingCart $cart): void {
                $cart->addProduct(new ProductId('garlic sauce'), 250);
            })
            ->then(
                new ProductAddedToCart(new ProductId('garlic sauce'), 250)
            );
    }

    /** @test */
    public function when_the_price_of_a_product_changed_the_item_cannot_be_added(): void
    {
        $this
            ->given(
                new ShoppingCartInitiated(),
                new ProductAddedToCart(new ProductId('garlic sauce'), 250),
            )
            ->when(function (ShoppingCart $cart): void {
                $cart->addProduct(new ProductId('garlic sauce'), 300);
            })
            ->expectToFail(SorryCantAddProduct::becauseThePriceHasChanged())
            ->thenNothingShouldHaveHappened();
    }
}
