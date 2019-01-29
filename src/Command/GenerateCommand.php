<?php declare (strict_types=1);

namespace DodoIt\DibiEntity\Command;

use DodoIt\DibiEntity\Generator\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
		$this->setName('entity:generate')
			->setDescription('Generate entities from database');
		$this->addArgument('table', InputArgument::OPTIONAL);
		$this->addOption('query', NULL, InputOption::VALUE_OPTIONAL, 'Provide SQL query from which I can generate entity (WARNING: This will create view with name of table argument and DROP it afterwards)');

	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->generator->generate($input->getArgument('table'), $input->getOption('query'));
		return 0;
	}
}