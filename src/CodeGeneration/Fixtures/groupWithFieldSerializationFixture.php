<?php

namespace Group\With\FieldDeserialization;

use EventSauce\EventSourcing\Event;

final class WithFieldSerializers implements Event
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
    public static function fromPayload(array $payload): Event
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
