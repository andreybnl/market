<?php


namespace App\Command;

use App\Entity\QuenyLog;
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

class MarketCommandAlt extends Command
{
    protected static $defaultName = 'cron_prepared:market_command';

    private $condition;
    private $connector;
    private $client;
    private $container;
    private $supplierRepository;

    public function __construct(
        Condition $condition,
        Connector $connector,
        HttpClientInterface $client,
        ContainerInterface $container,
        SupplierRepository $supplierRepository
    )
    {
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
            ->addArgument('queny', InputArgument::REQUIRED)
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
        try {
    //        $this->condition->checkBusy();
    //        $this->condition->createBusy();
            $accessToken = $this->connector->getToken('PlantsMarket');
            $startTime = new \DateTime('now');
            $response = $this->connector->getContent($accessToken, 'PlantsMarket',
                $input->getOption('retry'), $input->getArgument('queny'));
            $entityManager = $this->container->get('doctrine')->getManager();
                $Log = new QuenyLog();
                $Log->setQuent($input->getOption('supplier') . ' ' . $input->getOption('queny'));
                $Log->setAnswer($response->getContent());
                $Log->setResponceCode($response->getStatusCode());
                $Log->setDateTime($startTime);
                $entityManager->persist($Log);
                $entityManager->flush();
                $this->condition->deleteBusy();
        } catch (\Exception $e) {
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}