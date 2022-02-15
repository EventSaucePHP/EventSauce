<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\CustomConstructors;

use EventSauce\EventSourcing\DummyAggregateRootId;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use PHPUnit\Framework\TestCase;

class CreatingAggregatesWithConstructorsInBaseClassesTest extends TestCase
{
    /**
     * @test
     */
    public function reconstituting_an_aggregate_that_has_a_constructor_in_a_base_class(): void
    {
        $repository = new InMemoryMessageRepository();
        $id = DummyAggregateRootId::generate();

        $concreteInstance = ConcreteChildClass::reconstituteFromEvents($id, $repository->retrieveAll($id));

        self::assertInstanceOf(ConcreteChildClass::class, $concreteInstance);
        self::assertEquals($id, $concreteInstance->aggregateRootId());
    }
}
