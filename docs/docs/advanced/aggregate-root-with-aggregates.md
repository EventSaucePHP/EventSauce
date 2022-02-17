---
permalink: /docs/advanced/aggregate-root-with-aggregates/
title: Aggregate root with aggregates
---

Sometimes you might want to split up your aggregate into multiple partial aggregates, 
while maintaining the transactionality of one parent aggregate. 

To add this functionality, you'd use the `AggregateRootWithAggregates` trait in your parent aggregate root. 
Your child aggregate root(s) need to implement the `EventSourcedAggregate` interface. 
You can use the `AggregateAppliesKnownEvents` trait in order to apply the events that are relevant for the partial.

In order to apply events to your child aggregates, you need to register the child using 
the `$this->registerAggregate($this->yourChildAggregate)` method in your parent aggregate.  

When you want to remove the aggregate, you can use the `$this->unregisterAggregate($this->yourChildAggregate)` method. 
This will stop the aggregate from receiving new events to apply. 

As example, lets imagine we have a `ShoppingCart` aggregate that manage the items inside a cart. 
We could put the logic that deals with adding and removing items to a sub aggregate: `CartItems`.

It's important to note, that we'd still only call methods on the ShoppingCart Aggregate. 
A naive, simple example of a shopping cart with a CartItems sub aggregate could look like this: 


```php
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
```

```php
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
```
