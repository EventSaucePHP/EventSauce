<?php

declare(strict_types=1);

namespace DefinedWith\Yaml;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class WeWentYamling implements SerializablePayload
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
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new WeWentYamling(
            \Ramsey\Uuid\Uuid::fromString($payload['reference']),
            (string) $payload['slogan']
        );
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

final class HideFinancialDetailsOfFraudulentCompany implements SerializablePayload
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
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new HideFinancialDetailsOfFraudulentCompany(
            \Ramsey\Uuid\Uuid::fromString($payload['companyId'])
        );
    }

    public function toPayload(): array
    {
        return [
            'companyId' => $this->companyId->toString(),
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withCompanyId(\Ramsey\Uuid\UuidInterface $companyId): HideFinancialDetailsOfFraudulentCompany
    {
        return new HideFinancialDetailsOfFraudulentCompany(
            $companyId
        );
    }
}

final class GoYamling implements SerializablePayload
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
    public static function fromPayload(array $payload): SerializablePayload
    {
        return new GoYamling(
            \Ramsey\Uuid\Uuid::fromString($payload['reference']),
            (string) $payload['slogan']
        );
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
    public function withReference(\Ramsey\Uuid\UuidInterface $reference): GoYamling
    {
        $clone = clone $this;
        $clone->reference = $reference;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withSlogan(string $slogan): GoYamling
    {
        return new GoYamling(
            \Ramsey\Uuid\Uuid::fromString("c0b47bc5-2aaa-497b-83cb-11d97da03a95"),
            $slogan
        );
    }
}
