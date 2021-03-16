<?php

namespace App\Command;

use App\Entity\QuenyLog;
use App\Model\Condition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\MarketProductRepository;
use App\Entity\MarketProduct;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;

class MarketSaveProduct extends Command
{
    protected static $defaultName = 'cron_prepared:market_product_save';

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
            ->setDescription('Run market product save')
            ->setHelp('This command allows you to market product save...')
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
        try {
            if ($this->condition->checkBusy()) {
                throw new \Exception('Another process not terminated');
            } else {
                $this->condition->createBusy();
                $token = $this->connector->getToken('PlantsMarket');
                $startTime = new \DateTime('now');
                $response = $this->connector->getContent($token, 'PlantsMarket', $input->getOption('retry'),
                    'v1/channel/' . $channel . '/product');
                $endTime = new \DateTime('now');
                $importResult = $this->storeMarketToDb($response);
                $this->taskLogger->TaskLogAdd(static::$defaultName, $startTime, strlen($response->getContent()), $endTime,
                    (integer)$startTime->diff($endTime)->format("%f"), $response->getStatusCode(),
                    $importResult[0], $importResult[0], $importResult[1]);
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

    private function storeMarketToDb($response)
    {
        $productCount = $error = null;
        $entityManager = $this->container->get('doctrine')->getManager();

        $data = json_decode($response->getContent(), true);
        if ($data) {
            foreach ($data['data'] as $result) {
                try {
                    $product = $this->marketProductRepository->findOneBy(['sku' => $result['sku']]);
                    if (!$product) {
                        $product = new MarketProduct();
                    }
                    //else if ($product->getEditTime() === $result['editTime']) {
                    //    continue; } //producr change detected without EditDAta change!
                    $product->setCreateTime(isset($result['createTime']) ? $result['createTime'] : '0');
                    $product->setEditTime(isset($result['editTime']) ? $result['editTime'] : '0');
                    $product->setSku($result['sku']);
                    $product->setRangeIdentifier(isset($result['rangeIdentifier']) ? $result['rangeIdentifier'] : '0');
                    $product->setCoreIdentifier(isset($result['coreIdentifier']) ? $result['coreIdentifier'] : '0');
                    $product->setRetailHash(isset($result['_retailHash']) ? $result['_retailHash'] : '0');
                    $product->setBatchHashArray(isset($result['_batchHashArray']) ? $result['_batchHashArray'] : '0');
                    $product->setBatchHash(isset($result['_batchHash']) ? $result['_batchHash'] : '0');
                    $product->setCoreHash(isset($result['_coreHash']) ? $result['_coreHash'] : '0');
                    $product->setProductRangeHash(isset($result['_productRangeHash']) ? $result['_productRangeHash'] : '0');
                    $product->setProductGroupHash(isset($result['_productGroupHash']) ? $result['_productGroupHash'] : '0');
                    $product->setProductCoreHash(isset($result['_productCoreHash']) ? $result['_productCoreHash'] : '0');
                    $product->setBaseHash(isset($result['_baseHash']) ? $result['_baseHash'] : '0');
                    $product->setNameSearch(isset($result['name_search']) ? $result['name_search'] : '0');
                    $product->setName(isset($result['name']) ? $result['name'] : '0');
                    $product->setRtlSizeCode(isset($result['size_code']) ? $result['size_code'] : '0');
                    $product->setBtchStock(isset($result['stock']) ? $result['stock'] : '0');
                    $product->setBatchIdOriginal(isset($result['batchIdOriginal']) ? $result['batchIdOriginal'] : '0');
                    $product->setBtchStockTotal(isset($result['btch_stock_total']) ? $result['btch_stock_total'] : '0');
                    $product->setBtchContainerType(isset($result['container_type']) ? $result['container_type'] : '0');
                    $product->setBtchUnitWeight(isset($result['btch_unit_weight']) ? $result['btch_unit_weight'] : '0');
                    $product->setBtchContainerSize(isset($result['btch_container_size']) ? $result['btch_container_size'] : '0');
                    $product->setBtchContainerShape(isset($result['shape']) ? $result['shape'] : '0');
                    $product->setBtchContainerContents(isset($result['container_contents']) ? $result['container_contents'] : '0');
                    $product->setBtchContainerDiameter(isset($result['btch_container_diameter']) ? $result['btch_container_diameter'] : '0');
                    $product->setChnPriceRetail(isset($result['price_retail']) ? $result['price_retail'] : '0');
                    if (isset($result['btch_stem_height'])) {
                        $product->setBtchStemHeight($result['btch_stem_height']);
                    }
                    if (isset($result['btch_height_to'])) {
                        $product->setBtchHeightTo($result['btch_height_to']);
                    }
                    if (isset($result['btch_height_from'])) {
                        $product->setBtchHeightFrom($result['btch_height_from']);
                    }
                    $entityManager->persist($product);
                    $entityManager->flush();
                    $productCount++;
                } catch (\Exception $e) {
                    $error++;
                }
            }
        }
        return array($error, $productCount);
    }
}