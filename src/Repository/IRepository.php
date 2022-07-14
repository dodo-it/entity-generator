<?php declare(strict_types = 1);

namespace DodoIt\EntityGenerator\Repository;

use DodoIt\EntityGenerator\Entity\Column;

interface IRepository
{

	/**
	 * @return string[]
	 */
	public function getTables(): array;

	/**
	 * @return Column[]
	 */
	public function getTableColumns(string $table): array;

	public function createViewFromQuery(string $name, string $query): void;

	public function dropView(string $name): void;

}
