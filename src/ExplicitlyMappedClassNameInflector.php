<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function is_array;
use function is_string;

final class ExplicitlyMappedClassNameInflector implements ClassNameInflector
{
    /** @var array<string, class-string>|null */
    private array|null $eventTypeToClassMap;

    /**
     * @param array<class-string, string|non-empty-array<string>> $classToEventTypeMap
     */
    public function __construct(
        private array $classToEventTypeMap
    ) {
    }

    public function classNameToType(string $className): string
    {
        $type = $this->classToEventTypeMap[$className] ?? null;
        is_array($type) && $type = $type[0] ?? null;

        if ( ! is_string($type)) {
            throw UnableToInflectClassName::mappingIsNotDefined($className);
        }

        return $type;
    }

    public function typeToClassName(string $eventType): string
    {
        $this->eventTypeToClassMap ??= $this->createConsumerMap();
        $className = $this->eventTypeToClassMap[$eventType] ?? null;

        if ($className === null) {
            throw UnableToInflectEventType::mappingIsNotDefined($eventType);
        }

        return $className;
    }

    public function instanceToType(object $instance): string
    {
        return $this->classNameToType(get_class($instance));
    }

    /**
     * On the first consumption, create a optimized reversed lookup map.
     *
     * @return array<string, class-string>
     */
    private function createConsumerMap(): array
    {
        $map = [];

        foreach ($this->classToEventTypeMap as $className => $eventType) {
            if (is_string($eventType)) {
                $map[$eventType] = $className;
            } else {
                foreach ($eventType as $e) {
                    $map[$e] = $className;
                }
            }
        }

        return $map;
    }
}
