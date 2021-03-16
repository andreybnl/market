<?php

namespace App\Command;

use App\Model\Condition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\MarketProductRepository;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;
use App\Command\MarketSaveBase;

class MarketSaveCategory extends Command
{
    protected static $defaultName = 'cron_prepared:market_product_category_save';

    private $baseCommand;
    private $condition;
    private $taskLogger;
    private $connector;
    private $client;
    private $container;
    private $marketProductRepository;
    private $supplierRepository;

    public function __construct(
        MarketSaveBase $baseCommand,
        Condition $condition,
        Connector $connector,
        TaskLogger $taskLogger,
        SupplierRepository $supplierRepository,
        HttpClientInterface $client,
        ContainerInterface $container,
        MarketProductRepository $marketProductRepository
    )
    {
        $this->baseCommand = $baseCommand;
        $this->condition = $condition;
        $this->connector = $connector;
        $this->taskLogger = $taskLogger;
        $this->supplierRepository = $supplierRepository;
        $this->marketProductRepository = $marketProductRepository;
        $this->client = $client;
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Run market product stock save')
            ->setHelp('This command allows you to market product stock save...')
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
        $channel = '9dad6222fe469a393ffb0720';
        $query = 'v1/channel/' . $channel . '/product/category';
        $requiredFildsArray = ['category'];
        try {
            $this->baseCommand->connect(static::$defaultName, 'PlantsMarket', $input->getOption('retry'),
                $query, $requiredFildsArray);
        } catch (\Exception $e) {
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

}