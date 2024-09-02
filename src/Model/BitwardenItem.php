<?php

namespace Jalismrs\Bitwarden\Model;

use DateTime;
use Exception;
use JsonException;
use RuntimeException;

class BitwardenItem extends AbstractObject
{
    private function __construct(
        private string $object,
        private string $id,
        private ?string $organizationId,
        private ?string $folderId,
        private int $type,
        private string $name,
        private ?BitwardenLogin $login,
        private array $collectionIds,
        private ?DateTime $revisionDate,
    ) {}

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): BitwardenItem
    {
        return new BitwardenItem(
            $data['object'] ?? throw new RuntimeException('Missing object in BitwardenItem json string'),
            $data['id'] ?? throw new RuntimeException('Missing id in BitwardenItem json string'),
            $data['organizationId'] ?? null,
            $data['folderId'] ?? null,
            $data['type'] ?? throw new RuntimeException('Missing type in BitwardenItem json string'),
            $data['name'] ?? throw new RuntimeException('Missing name in BitwardenItem json string'),
            isset($data['login']) ? BitwardenLogin::fromArray($data['login']) : null,
            $data['collectionIds'] ?? [],
            isset($data['revisionDate']) ? new DateTime($data['revisionDate']) : null,
        );
    }

    public function getObject(): string
    {
        return $this->object;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrganizationId(): ?string
    {
        return $this->organizationId;
    }

    public function getFolderId(): ?string
    {
        return $this->folderId;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogin(): ?BitwardenLogin
    {
        return $this->login;
    }

    public function getCollectionIds(): array
    {
        return $this->collectionIds;
    }

    public function getRevisionDate(): ?DateTime
    {
        return $this->revisionDate;
    }
}