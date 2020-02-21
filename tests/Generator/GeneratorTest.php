<?php declare (strict_types = 1);

namespace DodoIt\EntityGenerator\Tests;

use DodoIt\EntityGenerator\Entity\Column;
use DodoIt\EntityGenerator\Generator\Config;
use DodoIt\EntityGenerator\Generator\Generator;
use DodoIt\EntityGenerator\Repository\IRepository;
use Nette\NotSupportedException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
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
			'Default' => null,
			'Extra' => 'auto_increment',
		]);
		$this->tableColumns[] = new Column([
			'Field' => 'title',
			'Type' => 'varchar(25)',
			'Null' => 'YES',
			'Key' => null,
			'Default' => null,
			'Extra' => null,
		]);
		$this->tableColumns[] = new Column([
				'Field' => 'published',
				'Type' => 'tinyint(1)',
				'Null' => 'NO',
				'Key' => null,
				'Default' => 1,
				'Extra' => null,
			]);
		$this->tableColumns[] = new Column([
			'Field' => 'created_at',
			'Type' => 'datetime',
			'Null' => 'YES',
			'Key' => null,
			'Default' => 1,
			'Extra' => null,
		]);
		$this->config = new Config();
		$this->config->namespace = 'DodoIt\EntityGenerator\Tests\TestEntities';
		$this->repository = $this->getMockForAbstractClass(IRepository::class);
		$this->generator = new Generator($this->repository, $this->config);
	}

	public function testGenerateEntity_WithPropertiesOnly_ShouldGenerateOnlyProperties()
	{
		$this->config->generateProperties = true;
		$this->config->generateGetters = false;
		$this->config->generateSetters = false;
		$this->config->generateColumnConstant = false;
		$this->config->generatePhpDocProperties = false;
		$this->repository->expects($this->once())->method('getTableColumns')->with('articles')->willReturn($this->tableColumns);
		$this->config->path = __DIR__ . '/../TestEntities/';
		$this->generator->generateEntity('articles');
		$entityFile = $this->config->path . 'ArticleEntity.php';
		$this->assertFileExists($entityFile);
		include $entityFile;
		$entityClass = ClassType::from($this->config->namespace . '\ArticleEntity');
		$properties = $entityClass->getProperties();
		$this->assertCount(count($this->tableColumns), $properties);
		$this->assertEquals($properties['id']->getComment(), '@var int');
		$this->assertEquals($properties['title']->getComment(), '@var string');
		$this->assertEquals($properties['created_at']->getComment(), '@var \DateTimeInterface');
		unlink($entityFile);
	}

	public function testGenerateEntity_WithRewriteFalseAndPhpDocProperties_ShouldNotReGenerate()
	{
		//we've put published as integer intentionally in PhpDocPropertyEntity so if we don't rewrite this should stay int and not become bool
		$this->config->generatePhpDocProperties = true;
		$this->config->generateProperties = false;
		$this->config->generateColumnConstant = false;
		$this->repository->expects($this->once())->method('getTableColumns')
			->with('php_doc_properties')->willReturn($this->tableColumns);
		$this->config->path = __DIR__ . '/../TestEntities';
		$this->generator->generateEntity('php_doc_properties');
		$string = file_get_contents($this->config->path . '/PhpDocPropertyEntity.php');
		$this->assertNotFalse(strpos($string, 'property int $published'));
	}

	public function testGenerateEntity_WithMappingAndPhpDocProperties_ShouldGenerateMapping()
	{
		$this->config->generatePhpDocProperties = false;
		$this->config->generateProperties = false;
		$this->config->generateColumnConstant = false;
		$this->config->generateMapping = true;
		$this->repository->expects($this->once())->method('getTableColumns')
			->with('mapping_test')->willReturn($this->tableColumns);
		$this->config->path = __DIR__ . '/../TestEntities';
		$this->generator->generateEntity('mapping_test');
		$entityFile = $this->config->path . '/MappingTestEntity.php';
		$this->assertFileExists($entityFile);
		include $entityFile;
		$reflection = ClassType::from('DodoIt\EntityGenerator\Tests\TestEntities\MappingTestEntity');
		$mapping = $reflection->getProperty('mapping');
		$expected = [
			'id' => 'Id',
			'title' => 'Title',
			'published' => 'Published',
			'created_at' => 'CreatedAt',
		];
		$this->assertEquals($expected, $mapping->getValue());
		unlink($entityFile);
	}

	public function testGenerateEntity_WithGenerateConstant_ShouldGenerateConstants()
	{
	//we've put published as integer intentionally in PhpDocPropertyEntity so if we don't rewrite this should stay int and not become bool
		$this->config->generatePhpDocProperties = false;
		$this->config->generateProperties = false;
		$this->config->primaryKeyConstant = 'PK_CONSTANT';
		$this->config->generateColumnConstant = true;
		$this->repository->expects($this->once())->method('getTableColumns')
			->with('constants')->willReturn($this->tableColumns);
		$this->config->path = __DIR__ . '/../TestEntities';
		$entityFile = $this->config->path . '/ConstantEntity.php';
		$this->generator->generateEntity('constants');
		include $entityFile;

		$entityContents = file_get_contents($entityFile);
		$this->assertRegExp('/const TABLE\_NAME \= \'constants\'/', $entityContents);
		$this->assertRegExp('/const PK\_CONSTANT \= \'id\'/', $entityContents);
		$this->assertRegExp('/const ID \= \'id\'/', $entityContents);
		unlink($entityFile);
	}

	public function testGenerate_WithTableName_ShouldGenerateOnlyThatTable()
	{
		$this->config->path = __DIR__ . '/../TestEntities';
		$entityFile = $this->config->path . '/TestEntity.php';

		$this->repository->expects($this->never())->method('getTables');
		$this->repository->expects($this->once())->method('getTableColumns')
			->with('test')->willReturn($this->tableColumns);
		$this->generator->generate('test');
		$this->assertFileExists($entityFile);
		unlink($entityFile);
	}

	public function testGenerate_WithoutParameters_ShouldGenerateEntitiesForWholeTable()
	{
		$this->config->path = __DIR__ . '/../TestEntities';

		$this->repository->expects($this->once())->method('getTables')->willReturn(['table1', 'table2']);
		$this->repository->expects($this->exactly(2))->method('getTableColumns')
			->withConsecutive(['table1'], ['table2'])->willReturn($this->tableColumns);
		$this->generator->generate();

		$entityFile = $this->config->path . '/Table1Entity.php';
		$this->assertFileExists($entityFile);
		unlink($entityFile);
		$entityFile = $this->config->path . '/Table2Entity.php';
		$this->assertFileExists($entityFile);
		unlink($entityFile);
	}

	public function testGenerate_WithQuery_ShouldGenerateEntityFromQuery()
	{
		$this->config->path = __DIR__ . '/../TestEntities';

		$this->repository->expects($this->never())->method('getTables');
		$this->repository->expects($this->once())->method('getTableColumns')
			->with('query')->willReturn($this->tableColumns);
		$this->generator->generate('query', 'SELECT col1, col2 FROM bla');

		$entityFile = $this->config->path . '/QueryEntity.php';
		$this->assertFileExists($entityFile);
		unlink($entityFile);
	}

	public function testGenerateEntity_WithStrictlyTypedProperties_ShouldGenerateStrictlyTypedProperties()
	{
		//we've put published as integer intentionally in PhpDocPropertyEntity so if we don't rewrite this should stay int and not become bool
		$this->config->generatePhpDocProperties = false;
		$this->config->generateProperties = true;
		$this->config->generateGetters = false;
		$this->config->addDeclareStrictTypes = true;
		$this->config->strictlyTypedProperties = true;
		$this->config->tableConstant = null;
		$this->config->propertyVisibility = 'public';
		$this->config->generateColumnConstant = false;
		$this->config->addPropertyVarComment = false;
		$this->config->generateSetters = false;

		$file = new PhpFile();
		if (!method_exists($file, 'setStrictTypes')) {
			$this->expectException(NotSupportedException::class);
		}

		$this->repository->expects($this->once())->method('getTableColumns')
			->with('strictly_typed')->willReturn($this->tableColumns);
		$this->config->path = __DIR__ . '/../TestEntities';
		$entityFile = $this->config->path . '/StrictlyTypedEntity.php';
		$this->generator->generateEntity('strictly_typed');

		$entityContents = file_get_contents($entityFile);
		$this->assertRegExp('/declare strict_types/', $entityContents);
		$this->assertRegExp('/public int \$id;/', $entityContents);
		$this->assertRegExp('/public \?string \$title;/', $entityContents);
		$this->assertRegExp('/public bool \$published;/', $entityContents);
		$this->assertRegExp('/public \?\\\DateTimeInterface \$created\_at;/', $entityContents);
		unlink($entityFile);
	}

}
