<?php

namespace App\Command;

use App\Kernel;
use App\Service\ReflectionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateEntityCommand extends Command
{
    private ReflectionService $reflectionService;
    private Kernel $kernel;

    public function __construct(ReflectionService $reflectionService, Kernel $kernel)
    {  
        parent::__construct();

        $this->reflectionService = $reflectionService;
        $this->kernel = $kernel;
    }

    public function configure()
    {
        $this->setName('generate:extjs:entities');
        $this->setDescription('Generates Sencha ExtJS models');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheDir = $this->kernel->getProjectDir().'/public/bundles/doctrinereflection/';
        $this->reflectionService->createCache($cacheDir);

        return Command::SUCCESS;
    }
}
