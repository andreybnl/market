<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\MarketProductRepository;
use App\Model\Condition;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;

class AkeneoTestCreate extends Command
{
    protected static $defaultName = 'cron_prepared:test_akeneo_sync';

    private $condition;
    private $taskLogger;
    private $connector;
    private $client;
    private $container;
    private $marketProductRepository;
    private $supplierRepository;

    public function __construct(
        Condition $condition,
        Connector $connector,
        TaskLogger $taskLogger,
        SupplierRepository $supplierRepository,
        HttpClientInterface $client,
        ContainerInterface $container,
        MarketProductRepository $marketProductRepository
    )
    {
        $this->connector = $connector;
        $this->condition = $condition;
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
            ->setDescription('Run Akeneo sync')
            ->setHelp('This command allows you to market sync with Akeneo...')
            ->addArgument('retry', InputArgument::OPTIONAL)
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
        $success = $error = null;
        $accessToken = $this->connector->getToken('Akeneo');
        $startTime = new \DateTime('now');
        $products = $this->marketProductRepository->findBy(array(), array('id' => 'DESC'), 10, 0);
        try {
            if ($this->condition->checkBusy()) {
                throw new \Exception('Another process not terminated');
            } else {
                $this->condition->createBusy();
                foreach ($products as $product) {
                    $sku = $product->getSku();
                    $name = $product->getName();
                    $identifier = "test" . $sku;
                    $data = "{\"identifier\":\"$identifier\",\"family\":\"Planten\",\"categories\":[\"Sierui_Allium\"],\"values\":{\"name\":
        [{\"locale\":\"nl_NL\",\"scope\":\"haagplanten_net\",\"data\":\"$name\"}],\"websites\":
        [{\"locale\":null,\"scope\":null,\"data\":[\"at_b2b_1\",\"at_b2c_1\",\"at_b2c_2\",\"be_b2c_1\",\"ch_b2b_1\",\"ch_b2c_1\",
        \"ch_b2c_2\",\"de_b2b_1\",\"de_b2c_1\",\"de_b2c_2\",\"dk_b2c_1\",\"fi_b2c_1\",\"fr_b2b_1\",\"fr_b2c_1\",\"it_b2c_1\",
        \"nl_b2b_1\",\"nl_b2c_1\",\"nl_b2c_2\",\"no_b2c_1\",\"se_b2c_1\"]}],\"tax_class\":[{\"locale\":null,\"scope\":
        null,\"data\":\"HIGH\"}],\"config_name\":[{\"locale\":\"fi_FI\",\"scope\":\"haagplanten_net\",\"data\":
        \"delokm8_fi2\"}],\"description\":[{\"locale\":\"da_DK\",\"scope\":\"haagplanten_net\",\"data\":\"danish desc\"}],
        \"config_url_key\":[{\"locale\":\"da_DK\",\"scope\":\"haagplanten_net\",\"data\":\"test223456\"},{\"locale\":
        \"fi_FI\",\"scope\":\"haagplanten_net\",\"data\":\"test\"}],\"meta_description\":[{\"locale\":
        \"fi_FI\",\"scope\":\"haagplanten_net\",\"data\":\"test_fi(md)\"}],\"config_metadescription\":[{\"locale\":
        \"fi_FI\",\"scope\":\"haagplanten_net\",\"data\":\"test_fi(cmd)\"}],\"shipment_transport_type\":[{\"locale\":
        null,\"scope\":null,\"data\":[\"PALLET\"]}],\"shipment_transport_height\":[{\"locale\":null,\"scope\":
        null,\"data\":\"GREATER_THAN_X\"}],\"shipment_points\":[{\"locale\":null,\"scope\":null,\"data\":
        \"3.0000\"}],\"shipment_availability_from\":[{\"locale\":null,\"scope\":null,\"data\":
        \"1\"}],\"shipment_availability_till\":[{\"locale\":null,\"scope\":null,\"data\":\"49\"}]}}";
                    $response = $this->connector->getContent($accessToken, 'Akeneo',
                        $input->getOption('retry'), "rest/v1/products/" . "test" . "$sku", 'PATCH', $data);
                    if ($response->getStatusCode() == '201' || $response->getStatusCode() == '204') {
                        $success++;
                    } else {
                        $error++;
                    }
                }
                $endTime = new \DateTime('now');
                $this->taskLogger->TaskLogAdd(static::$defaultName, $startTime, strlen($response->getContent()), $endTime,
                    (integer)$startTime->diff($endTime)->format("%f"), $response->getStatusCode(),
                    $success + $error, $success, $error);
            }
        } catch (\Exception $e) {
            $this->condition->deleteBusy();
            $endTime = new \DateTime('now');
            $this->taskLogger->TaskLogAdd(static::$defaultName, $startTime, 0, $endTime,
                (integer)$startTime->diff($endTime)->format("%f"), $e->getMessage(),
                0, 0, 0);
            return Command::FAILURE;
        }
        $this->condition->deleteBusy();
        return Command::SUCCESS;
    }
}