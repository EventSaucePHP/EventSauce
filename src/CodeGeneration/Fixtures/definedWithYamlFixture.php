<?php

declare(strict_types=1);

namespace DefinedWith\Yaml;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class WeWentYamling implements SerializablePayload
{
    public function __construct(
        private \Ramsey\Uuid\UuidInterface $reference,
        private string $slogan,
        private ?string $title = null,
        private ?string $description = null
    ) {
    }

    public function reference(): \Ramsey\Uuid\UuidInterface
    {
        return $this->reference;
    }

    public function slogan(): string
    {
        return $this->slogan;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public static function fromPayload(array $payload): self
    {
        return new WeWentYamling(
            \Ramsey\Uuid\Uuid::fromString($payload['reference']),
            (string) $payload['slogan'],
            (string) $payload['title'],
            (string) $payload['description']
        );
    }

    public function toPayload(): array
    {
        return [
            'reference' => $this->reference->toString(),
            'slogan' => (string) $this->slogan,
            'title' => isset($this->title) ? (string) $this->title : null,
            'description' => (string) $this->description,
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
    public function withTitle(string $title): WeWentYamling
    {
        $clone = clone $this;
        $clone->title = $title;

        return $clone;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withSloganAndDescription(string $slogan, string $description): WeWentYamling
    {
        return new WeWentYamling(
            \Ramsey\Uuid\Uuid::fromString("c0b47bc5-2aaa-497b-83cb-11d97da03a95"),
            $slogan,
            (string) 'Some Example Title',
            $description
        );
    }
}

final class HideFinancialDetailsOfFraudulentCompany implements SerializablePayload
{
    public function __construct(
        private \Ramsey\Uuid\UuidInterface $companyId
    ) {
    }

    public function companyId(): \Ramsey\Uuid\UuidInterface
    {
        return $this->companyId;
    }

    public static function fromPayload(array $payload): self
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
    public function __construct(
        private \Ramsey\Uuid\UuidInterface $reference,
        private string $slogan
    ) {
    }

    public function reference(): \Ramsey\Uuid\UuidInterface
    {
        return $this->reference;
    }

    public function slogan(): string
    {
        return $this->slogan;
    }

    public static function fromPayload(array $payload): self
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
