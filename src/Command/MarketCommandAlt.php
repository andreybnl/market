<?php


namespace App\Command;

use App\Entity\QuenyLog;
use http\Exception;
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
        $entityManager = $this->container->get('doctrine')->getManager();
        try {
            if ($this->condition->checkBusy()) {
                throw new \Exception('Another process not terminated');
            } else {
                $this->condition->createBusy();
                $accessToken = $this->connector->getToken('PlantsMarket');
                $response = $this->connector->getContent($accessToken, 'PlantsMarket',
                    $input->getOption('retry'), $input->getArgument('query'));
                $Log = new QuenyLog();
                $Log->setQuent('market_command' . ' ' . $input->getArgument('query'));
                $Log->setAnswer($response->getContent());
                $Log->setResponceCode($response->getStatusCode());
                $Log->setDateTime($startTime);
                $entityManager->persist($Log);
                $entityManager->flush();
            }
        } catch (\Exception $e) {
            $this->condition->deleteBusy();
            $Log = new QuenyLog();
            $Log->setQuent('market_command' . ' ' . $input->getArgument('query'));
            $Log->setAnswer($e->getMessage());
            $Log->setResponceCode('0');
            $Log->setDateTime($startTime);
            $entityManager->persist($Log);
            $entityManager->flush();
            return Command::FAILURE;
        }
        $this->condition->deleteBusy();
        return Command::SUCCESS;
    }
}