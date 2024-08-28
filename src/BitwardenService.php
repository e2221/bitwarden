<?php

namespace Jalismrs\Bitwarden;

use Jalismrs\Bitwarden\Enum\TypeEnum;
use Jalismrs\Bitwarden\Model\BitwardenCollection;
use Jalismrs\Bitwarden\Model\BitwardenFolder;
use Jalismrs\Bitwarden\Model\BitwardenItem;
use Jalismrs\Bitwarden\Model\BitwardenObject;
use Jalismrs\Bitwarden\Model\BitwardenOrganization;
use Jalismrs\Bitwarden\Model\BitwardenStatus;
use Jalismrs\Bitwarden\Search\SearchOptions;
use JsonException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BitwardenService
{
    public function __construct(
        private BitwardenServiceDelegate $delegate,
    ) {}

    /**
     * @return BitwardenItem[]
     * @throws JsonException
     */
    public function searchItems(string $search): array
    {
        $session = $this->getSession();
        $output = $this->execCommand(BitwardenCommands::SEARCH_ITEMS_COMMAND(
            $session,
            $this->delegate->getOrganizationId(),
            $search,
        ));

		if($this->countItemsInJson($output) === 0) {
			return [];
		}

        return BitwardenItem::arrayFromJson($output);
    }

	/**
	 * @return BitwardenObject[]
	 * @throws JsonException
	 */
	public function searchItemsByType(
		TypeEnum|string $type,
		string|null $search = null,
		SearchOptions|null $options = null,
	): array {
		$session = $this->getSession();

		$output = $this->execCommand(BitwardenCommands::SEARCH_LIST_COMMAND(
			session: $session,
			organizationId: $this->delegate->getOrganizationId(),
			type: $type,
			search: $search,
			searchOptions: $options,
		));

		if ($this->countItemsInJson($output) === 0) {
			return [];
		}

		return match ($type) {
			TypeEnum::items => BitwardenItem::arrayFromJson($output),
			TypeEnum::collections => BitwardenCollection::arrayFromJson($output),
			TypeEnum::folders => BitwardenFolder::arrayFromJson($output),
			TypeEnum::organizations => BitwardenOrganization::arrayFromJson($output),
		};
	}

	/**
	 * @throws JsonException
	 */
	public function getItemById(string $id, TypeEnum $type = TypeEnum::items): BitwardenObject|null
	{
		$session = $this->getSession();

		$output = $this->execCommand(BitwardenCommands::GET_ITEM_COMMAND(
			session: $session,
			id: $id,
			type: $type,
		));

		if ($this->countItemsInJson($output) === 0) {
			return null;
		}

		return match ($type) {
			TypeEnum::items => BitwardenItem::arrayFromJson($output)[0],
			TypeEnum::collections => BitwardenCollection::arrayFromJson($output)[0],
			TypeEnum::folders => BitwardenFolder::arrayFromJson($output)[0],
			TypeEnum::organizations => BitwardenOrganization::arrayFromJson($output)[0],
		};
	}

	private function countItemsInJson(string $json): int
	{
		return count(json_decode($json, true));
	}

    /**
     * @throws JsonException
     * @throws ProcessFailedException
     */
    private function getStatus(?string $session): BitwardenStatus
    {
        $output = $this->execCommand(BitwardenCommands::STATUS_COMMAND($session));
        return BitwardenStatus::fromJson($output);
    }

    /**
     * @throws JsonException
     */
    private function getSession(): string
    {
        $session = $this->delegate->restoreSession();
        $status = $this->getStatus($session);

        if ($session !== null && $status->getStatus() === BitwardenStatus::STATUS_UNLOCKED) {
            return $session;
        }

        else if ($status->getStatus() === BitwardenStatus::STATUS_LOCKED) {
            $session = $this->execCommand(BitwardenCommands::UNLOCK_COMMAND(
                $this->delegate->getUserPassword(),
            ));
        }

        else if ($status->getStatus() === BitwardenStatus::STATUS_UNAUTHENTICATED) {
            $session = $this->execCommand(BitwardenCommands::LOGIN_COMMAND(
                $this->delegate->getUserEmail(),
                $this->delegate->getUserPassword(),
            ));
        }

        $this->delegate->storeSession($session);

        return $session;
    }

    /**
     * @throws ProcessFailedException
     */
    private function execCommand(array $command): string
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}