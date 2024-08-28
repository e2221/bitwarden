<?php

namespace Jalismrs\Bitwarden\Model;

use Exception;
use JsonException;
use RuntimeException;

abstract class AbstractObject implements BitwardenObject
{
	/**
	 * @throws JsonException
	 * @throws Exception
	 */
	public static function fromJson(string $json): BitwardenObject
	{
		$data = json_decode($json, true, $depth=512, JSON_THROW_ON_ERROR);
		return static::fromArray($data);
	}

	/**
	 * @return BitwardenItem[]
	 * @throws JsonException
	 * @throws Exception
	 */
	public static function arrayFromJson(string $json): array
	{
		$data = json_decode($json, true, $depth=512, JSON_THROW_ON_ERROR);

		if (array_keys($data) !== range(0, count($data) - 1)) {
			throw new RuntimeException('Given array is not sequential');
		}

		return array_map(fn ($it) => static::fromArray($it), $data);
	}
}