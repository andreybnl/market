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

class CustomCommand extends Command
{
    protected static $defaultName = 'cron:custom_command';

    private $connector;
    private $client;
    private $container;
    private $supplierRepository;

    public function __construct(
        Connector $connector,
        HttpClientInterface $client,
        ContainerInterface $container,
        SupplierRepository $supplierRepository
    )
    {
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
            ->addArgument('queny', InputArgument::REQUIRED)
            ->addArgument('supplier', InputArgument::REQUIRED)
            ->addArgument('type', InputArgument::REQUIRED)
            ->addArgument('retry', InputArgument::OPTIONAL);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accessToken = $this->connector->getToken($input->getArgument('supplier'));
        $startTime = new \DateTime('now');
        $response = $this->connector->getContent($accessToken, $input->getArgument('supplier'),
            $input->getArgument('retry'), $input->getArgument('queny') );
        $entityManager = $this->container->get('doctrine')->getManager();
        if ($response)
        {
            $Log = new QuenyLog();
            $Log->setQuent($input->getArgument('supplier') . ' ' . $input->getArgument('queny'));
            $Log->setAnswer($response->getContent());
            $Log->setResponceCode($response->getStatusCode());
            $Log->setDateTime($startTime);
            $entityManager->persist($Log);
            $entityManager->flush();
            return Command::SUCCESS;
        }
        else return Command::FAILURE;
    }
}