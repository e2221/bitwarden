<?php

namespace Jalismrs\Bitwarden\Model;

use Exception;
use RuntimeException;

final class BitwardenOrganization extends AbstractObject
{
	private function __construct(
		private string $object,
		private string $id,
		private string $name,
	) {
	}

	/**
	 * @throws Exception
	 */
	public static function fromArray(array $data): BitwardenOrganization
	{
		return new BitwardenOrganization(
			$data['object'] ?? throw new RuntimeException('Missing object in BitwardenItem json string'),
			$data['id'] ?? throw new RuntimeException('Missing id in BitwardenItem json string'),
			$data['name'] ?? throw new RuntimeException('Missing name in BitwardenItem json string'),
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


	public function getName(): string
	{
		return $this->name;
	}
}