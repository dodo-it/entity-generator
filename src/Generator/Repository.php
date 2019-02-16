<?php declare (strict_types=1);

namespace DodoIt\EntityGenerator\Generator;

use Dibi\Connection;
use Dibi\Result;


class Repository
{

	/**
	 * @var Connection
	 */
	private $db;


	public function __construct(Connection $db)
	{
		$this->db = $db;
	}


	/**
	 * @return string[]
	 */
	public function getTables(): array
	{
		return $this->db->fetchPairs('SHOW TABLES');
	}


	/**
	 * @throws \Dibi\Exception
	 */
	public function getTableColumns(string $table): array
	{
		return $this->db->query('SHOW COLUMNS FROM %n', $table)->setRowClass(Column::class)->fetchAll();
	}


	public function createViewFromQuery(string $name, string $query): Result
	{
		return $this->db->query('CREATE VIEW %n AS ' . $query, $name);
	}


	public function dropView(string $name): Result
	{
		return $this->db->query('DROP VIEW %n', $name);
	}
}