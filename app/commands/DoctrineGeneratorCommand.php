<?php

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Tools\EntityGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DoctrineGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'doctrine:generate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Doctrine entity generator';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$entity = $this->argument('entityName');
		$meta = new ClassMetadataInfo($entity);
		$meta->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);
		$meta->mapField(['fieldName' => 'id', 'type' => 'integer', 'id' => true]);
		$meta->setPrimaryTable( ['name' => strtolower($entity) . 's'] );
		foreach( explode(',', $this->option('fields')) as $field ) {
			$chunks = explode(':', $field);
			if( count($chunks) < 2 ) {
				$this->error("Error in syntax.");
				return;
			}
			$fieldName = $chunks[0];
			$type = $chunks[1];
			$meta->mapField(['fieldName' => $fieldName, 'type' => $type]);
		}
		$entityGenerator = new EntityGenerator();
        $entityGenerator->setGenerateAnnotations(true);
		$entityGenerator->setGenerateStubMethods(true);
        $entityCode = $entityGenerator->generateEntityClass($meta);

		$path = $this->argument('path');
		if( empty($path) ) {
			$path = base_path('src/App/Entity/') . $entity .'.php';
		}
		file_put_contents($path, $entityCode);

		$this->info("Generated entity {$this->argument('entityName')} with fields {$this->option('fields')}.");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('entityName', InputArgument::REQUIRED, 'Name of the entity.'),
			array('path', InputArgument::OPTIONAL, 'Target path.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('fields', null, InputOption::VALUE_REQUIRED, 'List of entity fields.', null),
		);
	}

}
