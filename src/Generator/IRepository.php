<?php

namespace DodoIt\EntityGenerator\Generator;

interface IRepository
{

	/**
	 * @return string[]
	 */
	public function getTables(): array;

	public function getTableColumns(string $table): array;

	public function createViewFromQuery(string $name, string $query): void;

	public function dropView(string $name): void;
}