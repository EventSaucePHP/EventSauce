<?php

namespace Group\With\FieldDeserialization;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class WithFieldSerializers implements SerializableEvent
{
    /**
     * @var array
     */
    private $items;

    public function __construct(
        array $items
    ) {
        $this->items = $items;
    }

    public function items(): array
    {
        return $this->items;
    }
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new WithFieldSerializers(
            array_map(function ($property) {
                return ['property' => $property];
            }, $payload['items']));
    }

    public function toPayload(): array
    {
        return [
            'items' => array_map(function ($item) {
                return $item['property'];
            }, $this->items),
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withItems(array $items): WithFieldSerializers
    {
        return new WithFieldSerializers(
            $items
        );
    }
}
