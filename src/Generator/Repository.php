<?php declare (strict_types=1);

namespace DodoIt\DibiEntity\Generator;

use Dibi\Connection;

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
	 * @param string $table
	 * @return Column[]
	 * @throws \Dibi\Exception
	 */
	public function getTableColumns(string $table): array
	{
		return $this->db->query('SHOW COLUMNS FROM %n', $table)->setRowClass(Column::class)->fetchAll();
	}
}