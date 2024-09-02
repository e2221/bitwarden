<?php

namespace Jalismrs\Bitwarden;

use Jalismrs\Bitwarden\Enum\TypeEnum;
use Jalismrs\Bitwarden\Search\SearchOptions;

abstract class BitwardenCommands
{
    public static function STATUS_COMMAND(?string $session): array
    {
        return self::withOptions(['bw', 'status'], [
            '--session' => $session,
        ]);
    }

    public static function LOGIN_COMMAND(string $username, string $password): array
    {
        return ['bw', 'login', $username, $password, '--raw'];
    }

    public static function UNLOCK_COMMAND(string $password): array
    {
        return ['bw', 'unlock', '--raw', $password];
    }

    public static function SEARCH_ITEMS_COMMAND(string $session, ?string $organizationId, string $search): array
    {
        return self::withOptions(['bw', 'list', 'items'], [
            '--organizationid' => $organizationId,
            '--search' => $search,
            '--session' => $session,
        ]);
    }

	public static function SEARCH_LIST_COMMAND(
		string $session,
		string|null $organizationId,
		TypeEnum|string $type = TypeEnum::items,
		string|null $search = null,
		SearchOptions|null $searchOptions = null
	): array {
		if (is_string($type)) {
			$type = TypeEnum::from($type);
		}

		$params = ['--session' => $session];

		if ($organizationId) {
			$params['organizationid'] = $organizationId;
		}

		if ($search) {
			$params['--search'] = $search;
		}

		if ($searchOptions) {
			foreach ($searchOptions->getOptions() as $option) {
				$params['--' . $option->getOption()->value] = $option->getSearch();
			}
		}

		return self::withOptions(['bw', 'list', $type->value], $params);
	}

	public static function GET_ITEM_COMMAND(
		string $session,
		string $id,
		TypeEnum $type,
	): array {
		return self::withOptions(['bw', 'list', $type->value], [
			'--session' => $session,
			'--search' => $id
		]);
	}

    private static function withOptions(array $cmd, array $options): array
    {
        foreach ($options as $name => $value) {
            if ($value !== null && !empty($value)) {
                $cmd[] = $name;
                $cmd[] = $value;
            }
        }

        return $cmd;
    }
}