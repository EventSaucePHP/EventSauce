<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;

class ArrayLookupClassNameInflectorTest extends TestCase
{
    /** @test */
    public function it_can_lookup_a_class_name_and_return_the_configured_name(): void
    {
        $inflector = new ArrayLookupClassNameInflector([
            'test_event' => TestEvent::class,
        ]);

        $this->assertEquals('test_event', $inflector->classNameToType(TestEvent::class));
    }

    /** @test */
    public function it_throws_an_exception_when_lookup_is_not_configured(): void
    {
        $inflector = new ArrayLookupClassNameInflector([]);

        $this->expectExceptionObject(new \Exception('Configure ' . TestEvent::class . ' in event type lookup'));
        $inflector->classNameToType(TestEvent::class);
    }

    /** @test */
    public function it_can_retrieve_the_class_name_from_type(): void
    {
        $inflector = new ArrayLookupClassNameInflector([
            'test_event' => TestEvent::class,
        ]);

        $this->assertEquals(TestEvent::class, $inflector->typeToClassName('test_event'));
    }

    /** @test */
    public function it_throws_an_exception_when_class_cannot_be_found(): void
    {
        $inflector = new ArrayLookupClassNameInflector([]);

        $this->expectExceptionObject(new \Exception("Type 'test_event' not configured in event type lookup"));
        $inflector->typeToClassName('test_event');
    }

    /** @test */
    public function it_works_with_instance_to_type(): void
    {
        $inflector = new ArrayLookupClassNameInflector([
            'test_event' => TestEvent::class,
        ]);
        $event = new TestEvent();

        $this->assertEquals('test_event', $inflector->instanceToType($event));
    }
}

class TestEvent
{
}
