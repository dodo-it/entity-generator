<?php declare (strict_types=1);

namespace DodoIt\DibiEntity\Command;

use DodoIt\DibiEntity\Generator\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{

	/**
	 * @var Generator
	 */
	private $generator;

	public function __construct(Generator $generator)
	{
		parent::__construct();
		$this->generator = $generator;
	}


	protected function configure()
	{
		$this->setName('dibi-entity:generate')
			->setDescription('Generate entities from database');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->generator->generate();
		return 0;
	}
}