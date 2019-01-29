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
		$this->addOption('query-file', NULL, InputOption::VALUE_OPTIONAL, 'Provide file path that holds SQL query from which to generate entity (works in similar way as query but it loads query from file)');

	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$query = $input->getOption('query');
		if($input->getOption('query-file')) {
			$query = file_get_contents($input->getOption('query-file'));
		}
		$this->generator->generate($input->getArgument('table'), $query);
		return 0;
	}
}