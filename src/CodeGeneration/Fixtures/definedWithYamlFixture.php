<?php

namespace DefinedWith\Yaml;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class WeWentYamling implements SerializableEvent
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $reference;

    /**
     * @var string
     */
    private $slogan;

    public function __construct(
        \Ramsey\Uuid\UuidInterface $reference,
        string $slogan
    ) {
        $this->reference = $reference;
        $this->slogan = $slogan;
    }

    public function reference(): \Ramsey\Uuid\UuidInterface
    {
        return $this->reference;
    }

    public function slogan(): string
    {
        return $this->slogan;
    }
    public static function fromPayload(array $payload): SerializableEvent
    {
        return new WeWentYamling(
            \Ramsey\Uuid\Uuid::fromString($payload['reference']),
            (string) $payload['slogan']);
    }

    public function toPayload(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'slogan' => (string) $this->slogan,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function withReference(\Ramsey\Uuid\UuidInterface $reference): WeWentYamling
    {
        $clone = clone $this;
        $clone->reference = $reference;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withSlogan(string $slogan): WeWentYamling
    {
        return new WeWentYamling(
            \Ramsey\Uuid\Uuid::fromString("c0b47bc5-2aaa-497b-83cb-11d97da03a95"),
            $slogan
        );
    }
}

final class HideFinancialDetailsOfFraudulentCompany
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $companyId;

    public function __construct(
        \Ramsey\Uuid\UuidInterface $companyId
    ) {
        $this->companyId = $companyId;
    }

    public function companyId(): \Ramsey\Uuid\UuidInterface
    {
        return $this->companyId;
    }
}

final class GoYamling
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $reference;

    /**
     * @var string
     */
    private $slogan;

    public function __construct(
        \Ramsey\Uuid\UuidInterface $reference,
        string $slogan
    ) {
        $this->reference = $reference;
        $this->slogan = $slogan;
    }

    public function reference(): \Ramsey\Uuid\UuidInterface
    {
        return $this->reference;
    }

    public function slogan(): string
    {
        return $this->slogan;
    }
}
