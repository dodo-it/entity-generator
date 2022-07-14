<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Repository;

use DodoIt\EntityGenerator\Entity\Column;
use PDO;

class PdoRepository implements IRepository
{

	private PDO $db;

	public function __construct(PDO $db)
	{
		$this->db = $db;
	}

	/**
	 * @return string[]
	 */
	public function getTables(): array
	{
		return $this->db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN, 0); //@phpstan-ignore-line
	}

	/**
	 * @return Column[]
	 */
	public function getTableColumns(string $table): array
	{
		$query = $this->db->query('SHOW COLUMNS FROM `' . $table . '`');
		$query->setFetchMode(PDO::FETCH_CLASS, Column::class); //@phpstan-ignore-line

		return $query->fetchAll(); //@phpstan-ignore-line
	}

	public function createViewFromQuery(string $name, string $query): void
	{
		$this->db->query('CREATE VIEW `' . $name . '` AS ' . $query)->execute(); //@phpstan-ignore-line
	}

	public function dropView(string $name): void
	{
		$this->db->query('DROP VIEW `' . $name . '`')->execute(); //@phpstan-ignore-line
	}

}
