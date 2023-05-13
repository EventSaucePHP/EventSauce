## Code generation for EventSauce

```bash
composer require --dev eventsauce/pest-utilities
```

### Usage

First, create a base test case, as [described in the regular PHPUnit setup](https://eventsauce.io/docs/testing/#1-create-a-base-test-case-for-your-aggregate).

Next, use the base test case in your Pest tests:

```php
use function EventSauce\EventSourcing\PestTooling\expectToFail;
use function EventSauce\EventSourcing\PestTooling\given;
use function EventSauce\EventSourcing\PestTooling\nothingShouldHaveHappened;
use function EventSauce\EventSourcing\PestTooling\when;

uses(YourBaseTestCase::class);

it('you can use the object-oriented interface', function () {
    $this->given(
        new ShoppingCartInitiated(),
    )->when(function (ShoppingCart $cart): void {
        $cart->addProduct(new ProductId('garlic sauce'), 250);
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
```