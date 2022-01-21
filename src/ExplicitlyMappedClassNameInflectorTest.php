<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use PHPUnit\Framework\TestCase;

class ExplicitlyMappedClassNameInflectorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_lookup_a_class_name_and_return_the_configured_name(): void
    {
        $inflector = new ExplicitlyMappedClassNameInflector([
            DummyEvent::class => 'test_event',
        ]);

        $this->assertEquals('test_event', $inflector->classNameToType(DummyEvent::class));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_lookup_is_not_configured(): void
    {
        $inflector = new ExplicitlyMappedClassNameInflector([]);

        $this->expectExceptionObject(UnableToInflectClassName::mappingIsNotDefined(DummyEvent::class));

        $inflector->classNameToType(DummyEvent::class);
    }

    /**
     * @test
     */
    public function it_can_retrieve_the_class_name_from_type(): void
    {
        $inflector = new ExplicitlyMappedClassNameInflector([
            DummyEvent::class => 'test_event',
        ]);

        $this->assertEquals(DummyEvent::class, $inflector->typeToClassName('test_event'));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_class_cannot_be_found(): void
    {
        $inflector = new ExplicitlyMappedClassNameInflector([]);

        $this->expectExceptionObject(UnableToInflectEventType::mappingIsNotDefined('test_event'));

        $inflector->typeToClassName('test_event');
    }

    /**
     * @test
     */
    public function it_can_map_an_event_to_an_event_type(): void
    {
        $inflector = new ExplicitlyMappedClassNameInflector([
            DummyEvent::class => 'test_event',
        ]);
        $event = new DummyEvent();

        $eventType = $inflector->instanceToType($event);

        $this->assertEquals('test_event', $eventType);
    }

    /**
     * @test
     */
    public function mapping_a_class_to_the_first_in_the_mapping_array(): void
    {
        $inflector = new ExplicitlyMappedClassNameInflector([
            DummyEvent::class => ['test_event', 'secondary_event'],
        ]);
        $event = new DummyEvent();

        $eventType = $inflector->instanceToType($event);

        $this->assertEquals('test_event', $eventType);
    }

    /**
     * @test
     */
    public function mapping_a_secondary_event_type_to_a_class(): void
    {
        $inflector = new ExplicitlyMappedClassNameInflector([
            DummyEvent::class => ['test_event', 'secondary_event'],
        ]);

        $className = $inflector->typeToClassName('secondary_event');

        $this->assertEquals(DummyEvent::class, $className);
    }
}
