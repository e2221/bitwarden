<?php

namespace Jalismrs\Bitwarden\Model;

use Exception;
use RuntimeException;

final class BitwardenCollection extends AbstractObject
{
    private function __construct(
        private string $object,
        private string $id,
        private string $organizationId,
        private string $name,
	    private string|null $externalId = null,

    ) {}

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): BitwardenCollection
    {
        return new BitwardenCollection(
            $data['object'] ?? throw new RuntimeException('Missing object in BitwardenItem json string'),
            $data['id'] ?? throw new RuntimeException('Missing id in BitwardenItem json string'),
            $data['organizationId'] ?? null,
            $data['name'] ?? throw new RuntimeException('Missing name in BitwardenItem json string'),
            $data['externalId'] ?? null,
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

    public function getOrganizationId(): string|null
    {
        return $this->organizationId;
    }


    public function getName(): string
    {
        return $this->name;
    }

	public function getExternalId(): string|null
	{
		return $this->externalId;
	}
}