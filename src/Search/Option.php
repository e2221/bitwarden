<?php

namespace Jalismrs\Bitwarden\Search;

use Jalismrs\Bitwarden\Enum\OptionEnum;

final readonly class Option
{
	public function __construct(
		private OptionEnum $option,
		private string $search,
	) {
	}
	
	public function getOption(): OptionEnum
	{
		return $this->option;
	}

	public function getSearch(): string
	{
		return $this->search;
	}
}