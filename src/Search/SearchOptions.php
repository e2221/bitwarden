<?php

namespace Jalismrs\Bitwarden\Search;

final class SearchOptions
{
	/**
	 * @var Option[]
	 */
	private array $options;
	
	public function addOption(Option $option)
	{
		$this->options[] = $option;
	}

	/**
	 * @return Option[]
	 */
	public function getOptions(): array
	{
		return $this->options;
	}
}