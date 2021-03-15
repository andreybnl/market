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

class ShopCtrlPriceUpdate extends Command
{
    protected static $defaultName = 'cron_prepared:test_shopctrl_price_sync';

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
            ->setDescription('Run ShopCtrl price sync')
            ->setHelp('This command allows you to market sync(price) with ShopCtrl...')
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
        $accessToken = $this->connector->getToken('ShopCtrl');
        $startTime = new \DateTime('now');
        $products = $this->marketProductRepository->findBy(array(), array('id' => 'DESC'), 10, 0);
        try {
            if ($this->condition->checkBusy()) {
                throw new \Exception('Another process not terminated');
            } else {
                $this->condition->createBusy();
                foreach ($products as $product) {
                    $sku = 'test' . $product->getSku();
                    $idRequest = json_decode($this->connector->getContent($accessToken, 'ShopCtrl',
                        $input->getOption('retry'), 'v1/ShopGroup/1/Products/' . $sku, 'GET')->getContent());
                    $id = json_decode(json_encode($idRequest), true)['Id'];
                    $price = $product->getChnPriceRetail();
                    $name = $product->getName();
                    $data = "{\"OrderComment\":\"\",\"MainImageChangedTimestamp\":null,\"SyncEnabled\":true,
                    \"ShopGroupId\":1,\"Note\":\"\",\"RefCode\":\"\",\"ProductBrand\":null,\"ProductVariantParentId\":null,
                    \"VariantInfo\":\"\",\"KeepStock\":true,\"IsShippable\":true,\"MinimumStock\":0,\"MainImageFileId\":null,
                    \"Published\":true,\"PriceExVat\":\"$price\",
                    \"ProductPropertyDefSetId\":null,\"TransportCategoryId\":null,\"ProductGroups\":[],\"Resources\":[],
                    \"ProductRelations\":[],\"ProductVariantPropertyDefIds\":[],\"ProductVariantIds\":[],
                    \"OriginCountryId\":null,\"OriginCountryCode\":null,\"Id\":\"$id\",\"Code\":\"$sku\",
                    \"Name\":\"$name\",\"Hscode\":\"\",\"EAN\":\"\",\"Type\":0}";
                    $response = $this->connector->getContent($accessToken, 'ShopCtrl',
                        $input->getOption('retry'), 'v1/products', 'PUT', $data);
                    if ($response->getStatusCode() == '200') {
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