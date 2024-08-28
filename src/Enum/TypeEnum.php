<?php

namespace Jalismrs\Bitwarden\Enum;

enum TypeEnum: string
{
	case items = 'items';
	case folders = 'folders';
	case collections = 'collections';
	case organizations = 'organizations';
}
