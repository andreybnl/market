<?php

namespace App\Command;

use App\Entity\SpeedChannels;
use App\Repository\SpeedChannelsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Model\TaskLogger;

class SyncCatalog extends Command
{
    protected static $defaultName = 'cron_prepared:speed_sync-catalog';

    private $taskLogger;
    private $client;
    private $container;
    private $speedChannelsRepository;

    public function __construct(
        HttpClientInterface $client,
        TaskLogger $taskLogger,
        ContainerInterface $container,
        SpeedChannelsRepository $speedChannelsRepository
    )
    {
        $this->client = $client;
        $this->container = $container;
        $this->taskLogger = $taskLogger;
        $this->speedChannelsRepository = $speedChannelsRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Run speed catalog sync')
            ->setHelp('This command allows you to Run speed catalog sync...')
            ->addArgument('retry', InputArgument::OPTIONAL)
            ->addOption(
                'iterations',
                'retry',
                InputOption::VALUE_OPTIONAL,
                'How many times should the queny repeated?',
                2
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //test query, not for production!
        $url = 'https://speed-dev.hyperflux.io/api/channels/channel/';
        $login = 'andrey.b';
        $password = '2LFwmHEwH98xiP50t13PnmfOaHegWq';
        $headers = array(
            'auth_basic' => array("username" => $login,
                "password" => $password)
        );
        $startTime = new \DateTime('now');
        $response = $this->client->request(
            'GET', $url, $headers);
        $endTime = new \DateTime('now');
        $data = json_decode($response->getContent(), true);
        if ($data) {
            $chanelCount = null;
            $entityManager = $this->container->get('doctrine')->getManager();
            foreach ($data as $result) {
                $channel = $this->speedChannelsRepository->findOneBy(['code' => $result['code']]);
                if (!$channel) {
                    $channel = new SpeedChannels();
                }
                $channel->setName($result['name']);
                $channel->setCurrencyCode($result['currency_code']);
                $channel->setCurrencyRate($result['currency_rate']);
                $channel->setVatPercentageHigh($result['vat_percentage_high']);
                $channel->setVatPercentageLow($result['vat_percentage_low']);
                $entityManager->persist($channel);
                $entityManager->flush();
                $chanelCount++;
            }
           // abs($startTime->getTimestamp()-$endTime->getTimestamp());
            $diff = $startTime->diff($endTime)->format("%f");
            //work with logger entity
            $this->taskLogger->CronLogUpdate(static::$defaultName, $startTime, $endTime, strval($diff) . ' us', $response->getStatusCode());
            $this->taskLogger->TaskLogAdd(static::$defaultName, $startTime, strlen($response->getContent()),
                $endTime, (integer)$diff, $response->getStatusCode(), $chanelCount, $chanelCount);
            return Command::SUCCESS;
        } else return Command::FAILURE;
    }
}