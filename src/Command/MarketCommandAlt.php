<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\Condition;
use App\Model\TaskLogger;

class MarketCommandAlt extends Command
{
    protected static $defaultName = 'cron_prepared:market_command';

    private $taskLogger;
    private $condition;
    private $connector;
    private $client;
    private $container;
    private $supplierRepository;

    public function __construct(
        TaskLogger $taskLogger,
        Condition $condition,
        Connector $connector,
        HttpClientInterface $client,
        ContainerInterface $container,
        SupplierRepository $supplierRepository
    )
    {
        $this->taskLogger = $taskLogger;
        $this->condition = $condition;
        $this->connector = $connector;
        $this->client = $client;
        $this->container = $container;
        $this->supplierRepository = $supplierRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Run any Market command')
            ->setHelp('This command allows you to Run any Market command...')
            ->addArgument('query', InputArgument::REQUIRED)
            ->addOption(
                'retry',
                'retry',
                InputOption::VALUE_REQUIRED,
                'How many times should the queny repeated?',
                2
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = new \DateTime('now');
        try {
            if ($this->condition->checkBusy()) {
                throw new \Exception('Another process not terminated');
            } else {
                $this->condition->createBusy();
                $accessToken = $this->connector->getToken('PlantsMarket');
                $response = $this->connector->getContent($accessToken, 'PlantsMarket',
                    $input->getOption('retry'), $input->getArgument('query'));
                $this->taskLogger->quenyLog('market_command' . ' ' . $input->getArgument('query'),
                    $response->getContent(), $response->getStatusCode(), $startTime);

                $endTime = new \DateTime('now');
                $this->taskLogger->TaskLogAdd(static::$defaultName . ' ' . $input->getArgument('query'),
                    $startTime, strlen($response->getContent()), $endTime,
                    (integer)$startTime->diff($endTime)->format("%f"), $response->getStatusCode(), 0, 0);

            }
        } catch (\Exception $e) {
            $this->condition->deleteBusy();
            $this->taskLogger->quenyLog('market_command' . ' ' . $input->getArgument('query'),
                $e->getMessage(), 0, $startTime);
            return Command::FAILURE;
        }
        $this->condition->deleteBusy();
        return Command::SUCCESS;
    }
}