<?php

declare(strict_types=1);

namespace Group\With\FieldDeserialization;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class WithFieldSerializers implements SerializablePayload
{
    public function __construct(
        private array $items
    ) {
    }

    public function items(): array
    {
        return $this->items;
    }

    public static function fromPayload(array $payload): self
    {
        return new WithFieldSerializers(
            array_map(function ($property) {
                return ['property' => $property];
            }, $payload['items'])
        );
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
