<?php declare (strict_types = 1);

namespace Generator;

use App\Models\Entities\ArticleEntity;
use DodoIt\EntityGenerator\Entity\Column;
use DodoIt\EntityGenerator\Generator\Config;
use DodoIt\EntityGenerator\Generator\Generator;
use DodoIt\EntityGenerator\Repository\IRepository;
use Nette\PhpGenerator\ClassType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var IRepository|MockObject
	 */
	private $repository;

	/**
	 * @var Column[]
	 */
	private $tableColumns = [];

	/**
	 * @var Generator
	 */
	private $generator;

	protected function setUp(): void
	{
		$this->tableColumns[] = new Column([
			'Field' => 'id',
			'Type' => 'int(11)',
			'Null' => 'NO',
			'Key' => 'PRI',
			'Default' => NULL,
			'Extra' => 'auto_increment'
		]);
		$this->tableColumns[] = new Column([
			'Field' => 'title',
			'Type' => 'varchar(25)',
			'Null' => 'YES',
			'Key' => NULL,
			'Default' => NULL,
			'Extra' => NULL
		]);
		$this->tableColumns[] = new Column([
				'Field' => 'published',
				'Type' => 'tinyint(1)',
				'Null' => 'NO',
				'Key' => NULL,
				'Default' => 1,
				'Extra' => NULL
			]);
		$this->tableColumns[] = new Column([
			'Field' => 'created_at',
			'Type' => 'datetime',
			'Null' => 'YES',
			'Key' => NULL,
			'Default' => 1,
			'Extra' => NULL
		]);
		$this->config = new Config();
		$this->repository = $this->getMockForAbstractClass(IRepository::class);
		$this->generator = new Generator($this->repository, $this->config);
	}

	public function testGenerate_WithPropertiesOnly_ShouldGenerateOnlyProperties()
	{
		$this->config->generateProperties = true;
		$this->config->generateGetters = false;
		$this->config->generateSetters = false;
		$this->config->generateColumnConstant = false;
		$this->config->generatePhpDocProperties = false;
		$generator = new Generator($this->repository, $this->config);
		$this->repository->expects($this->once())->method('getTableColumns')->with('articles')->willReturn($this->tableColumns);
		$this->config->path = __DIR__;
		$generator->generateEntity('articles');
		$entityFile = __DIR__ . '/ArticleEntity.php';
		$this->assertFileExists($entityFile);
		include $entityFile;
		$entityClass = ClassType::from($this->config->namespace . '\ArticleEntity');
		$properties = $entityClass->getProperties();
		$this->assertCount(count($this->tableColumns), $properties);
		$this->assertEquals($properties['id']->getComment(), '@var int');
		$this->assertEquals($properties['title']->getComment(), '@var string');
		$this->assertEquals($properties['created_at']->getComment(), '@var \DateTime');
		unlink($entityFile);
	}
}