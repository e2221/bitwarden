<?php

namespace Jalismrs\Bitwarden\Model;

interface BitwardenObject
{
	public static function fromArray(array $data): BitwardenObject;

	public static function fromJson(string $json): BitwardenObject;

	public static function arrayFromJson(string $json): array;
}