<?php

namespace With\ManyRequiredFields;

use EventSauce\EventSourcing\Event;

final class ThisOne implements Event
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    public function __construct(
        string $title,
        string $description
    ) {
        $this->title = $title;
        $this->description = $description;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }
    public static function fromPayload(array $payload): Event
    {
        return new ThisOne(
            (string) $payload['title'],
            (string) $payload['description']);
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
