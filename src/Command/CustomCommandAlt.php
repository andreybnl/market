<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;

class CustomCommandAlt extends Command
{
    protected static $defaultName = 'cron_prepared:custom_command';

    private $taskLogger;
    private $connector;
    private $client;
    private $container;
    private $supplierRepository;

    public function __construct(
        TaskLogger $taskLogger,
        Connector $connector,
        HttpClientInterface $client,
        ContainerInterface $container,
        SupplierRepository $supplierRepository
    )
    {
        $this->taskLogger = $taskLogger;
        $this->connector = $connector;
        $this->client = $client;
        $this->container = $container;
        $this->supplierRepository = $supplierRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Run any CURL command')
            ->setHelp('This command allows you to Run any CURL command...')
            ->addOption(
                'queny',
                'queny',
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                'supplier', 'sup', InputOption::VALUE_REQUIRED)
            ->addOption('type', 'type', InputOption::VALUE_OPTIONAL, '', 'GET')
            ->addOption(
                'retry',
                'retry',
                InputOption::VALUE_OPTIONAL,
                'How many times should the queny repeated?',
                2
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('supplier') && $input->getOption('queny')) {
            $accessToken = $this->connector->getToken($input->getOption('supplier'));
            $startTime = new \DateTime('now');
            $response = $this->connector->getContent($accessToken, $input->getOption('supplier'),
                $input->getOption('retry'), $input->getOption('queny'));
            if ($response) {
                $this->taskLogger->quenyLog($input->getOption('supplier') . ' ' . $input->getOption('queny'),
                    $response->getContent(), $response->getStatusCode(), $startTime);
                //to tasks_log too?
                //it is possible to check - is this $defaultName present in scheduled table //? - not neccessary!
            }
            else{
                $this->taskLogger->quenyLog($input->getOption('supplier') . ' ' . $input->getOption('queny'),
                    0,0, $startTime);
            }
            return Command::SUCCESS;
        } else return Command::FAILURE;
    }
}