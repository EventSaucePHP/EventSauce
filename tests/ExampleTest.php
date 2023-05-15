<?php

use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ProductAddedToCart;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ProductId;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ShoppingCart;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ShoppingCartInitiated;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\SorryCantCheckout;
use PeventSauce\ShoppingCartTestCase;
use function EventSauce\EventSourcing\PestTooling\expectToFail;
use function EventSauce\EventSourcing\PestTooling\given;
use function EventSauce\EventSourcing\PestTooling\nothingShouldHaveHappened;
use function EventSauce\EventSourcing\PestTooling\when;

uses(ShoppingCartTestCase::class);

it('you can use the object-oriented interface', function () {
    $this->given(
        new ShoppingCartInitiated(),
    )->when(function (ShoppingCart $cart): void {
        $cart->add(new ProductId('garlic sauce'), 250);
    })->then(
        new ProductAddedToCart(new ProductId('garlic sauce'), 250)
    );
});

it('or the function based interface', function () {
    given(new ShoppingCartInitiated());
    when(function (ShoppingCart $cart): void {
        $cart->checkout();
    });
    expectToFail(SorryCantCheckout::becauseThereAreNoProductsInCart());
    nothingShouldHaveHappened();
});

it('or mix it all', function () {
    given(new ShoppingCartInitiated())
        ->when(function (ShoppingCart $cart): void {
            $cart->checkout();
        });
    expectToFail(SorryCantCheckout::becauseThereAreNoProductsInCart())
        ->nothingShouldHaveHappened();
});

it('can be chained into `it`')
    ->given(new ShoppingCartInitiated())
    ->when(fn (ShoppingCart $cart) => $cart->add(new ProductId('garlic sauce'), 250))
    ->then(new ProductAddedToCart(new ProductId('garlic sauce'), 250));
