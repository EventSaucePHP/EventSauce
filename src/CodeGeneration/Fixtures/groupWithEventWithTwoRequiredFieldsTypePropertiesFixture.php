<?php

declare(strict_types=1);

namespace With\ManyRequiredFields;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class ThisOne implements SerializablePayload
{
    public function __construct(
        private string $title,
        private string $description
    ) {
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public static function fromPayload(array $payload): self
    {
        return new ThisOne(
            (string) $payload['title'],
            (string) $payload['description']
        );
    }

    public function toPayload(): array
    {
        return [
            'title' => (string) $this->title,
            'description' => (string) $this->description,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withTitleAndDescription(string $title, string $description): ThisOne
    {
        return new ThisOne(
            $title,
            $description
        );
    }
}
