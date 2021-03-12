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
use App\Entity\MarketProduct;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;

class Excahnge_MA extends Command
{
    protected static $defaultName = 'cron_not_prepared:market_akeneo_sync';

    private $taskLogger;
    private $connector;
    private $client;
    private $container;
    private $marketProductRepository;
    private $supplierRepository;

    public function __construct(
        Connector $connector,
        TaskLogger $taskLogger,
        SupplierRepository $supplierRepository,
        HttpClientInterface $client,
        ContainerInterface $container,
        MarketProductRepository $marketProductRepository
    )
    {
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
            ->setDescription('Run market sync')
            ->setHelp('This command allows you to market sync with Akeneo...')
            ->addArgument('retry', InputArgument::OPTIONAL)
            ->addOption(
                'iterations',
                'retry',
                InputOption::VALUE_OPTIONAL,
                'How many times should the queny repeated?',
                2
            );
    }

    protected function execute_was(InputInterface $input, OutputInterface $output)
    {
        $this->sentToAkeneo();
        return Command::SUCCESS;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = '9dad6222fe469a393ffb0720';

        $token = $this->connector->getToken('PlantsMarket');
        if ($token) { //and Akeneo token futher!)
            $startTime = new \DateTime('now');
            $response = $this->connector->getContent($token, 'PlantsMarket', $input->getArgument('retry'),
                'v1/product/channel/' . $channel);
            $response_add = $this->connector->getContent($token, 'PlantsMarket', $input->getArgument('retry'),
                '/v1/product/category/' . $channel);                //if category will absent => remove!
            $endTime = new \DateTime('now');

            if ($response && $response_add) {
                $importResult = $this->storeMarketToDb($response, $response_add);
                $this->taskLogger->CronLogUpdate(static::$defaultName, $startTime, $endTime,
                    strval($startTime->diff($endTime)->format("%s:%f")), $response->getStatusCode());
                $this->taskLogger->TaskLogAdd(static::$defaultName, $startTime, strlen($response->getContent()),$endTime,
                    (integer)$startTime->diff($endTime)->format("%f"),$importResult[0], $importResult[0], $importResult[1]); //TODO check!
                //here will be request to Akeneo

            }
            return Command::SUCCESS;
        } else return Command::FAILURE;
    }

    private function storeMarketToDb($response, $response_add)
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
                    $product->setRtlSizeCode(isset($result['rtl_size_code']) ? $result['rtl_size_code'] : '0');
                    $product->setBtchStock(isset($result['btch_stock']) ? $result['btch_stock'] : '0');
                    $product->setBatchIdOriginal(isset($result['batchIdOriginal']) ? $result['batchIdOriginal'] : '0');
                    $product->setBtchStockTotal(isset($result['btch_stock_total']) ? $result['btch_stock_total'] : '0');
                    $product->setBtchContainerType(isset($result['btch_container_type']) ? $result['btch_container_type'] : '0');
                    $product->setBtchUnitWeight(isset($result['btch_unit_weight']) ? $result['btch_unit_weight'] : '0');
                    $product->setBtchContainerSize(isset($result['btch_container_size']) ? $result['btch_container_size'] : '0');
                    $product->setBtchContainerShape(isset($result['btch_container_shape']) ? $result['btch_container_shape'] : '0');
                    $product->setBtchContainerContents(isset($result['btch_container_contents']) ? $result['btch_container_contents'] : '0');
                    $product->setBtchContainerDiameter(isset($result['btch_container_diameter']) ? $result['btch_container_diameter'] : '0');
                    $product->setChnPriceRetail(isset($result['chn_price_retail']) ? $result['chn_price_retail'] : '0');
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
        $data_add = json_decode($response_add->getContent(), true);
        if ($data_add) {
            try {
                foreach ($data_add['data'] as $result) {
                    $product = $this->marketProductRepository->findOneBy(['sku' => $result['sku']]);
                    $product->setCategory(json_encode(isset($result['category']) ? $result['category'] : '0'));
                    $entityManager->persist($product);
                    $entityManager->flush();
                }
            } catch (\Exception $e) {
                $error++;
            }
        }
        return array($error, $productCount);
    }

    private function sentToAkeneo($retry = 3)
    {
        $success = null;
        $accessToken = $this->connector->getToken('Akeneo');
        //далі потрібно знайти які продукти змінилися/додані за період(типу день) та в циклі відправити їх в Акенео
        //поки їх небагато, то можна всі слати
        $products = $this->marketProductRepository->findAll();
        $startTime = new \DateTime('now');
        foreach ($products as $product) {
            $sku = $product->getSku();
            $data = "{\"identifier\":\"test. $sku\",\"family\":\"Planten\",\"categories\":[\"Sierui_Allium\"],\"values\":{\"name\":
        [{\"locale\":\"nl_NL\",\"scope\":\"haagplanten_net\",\"data\":\"test. $sku\"}],\"websites\":
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
                $retry, "rest/v1/products/$sku", 'PATCH', $data);
            if ($response->getStatusCode() == ('201' || '204')) {
                $success++;
            }
        }
        $endTime = new \DateTime('now');
        //логер додати?
        //виходить, 2 таски в одній - проблеми з репортом, треба мабуть обєднати...
    }

    private function sentToAkeneo2($retry = 3)
    {
        $success = null;
        $accessToken = $this->connector->getToken('Akeneo');

        $data = '{"identifier":"delokm889","family":"Planten","parent":null,"groups":[],"categories":["Sierui_Allium"],"enabled":true,"values":{"name":[{"locale":"nl_NL","scope":"haagplanten_net","data":"delokm8dutch2_16"}],"giftig":[{"locale":null,"scope":null,"data":false}],"websites":[{"locale":null,"scope":null,"data":["at_b2b_1","at_b2c_1","at_b2c_2","be_b2c_1","ch_b2b_1","ch_b2c_1","ch_b2c_2","de_b2b_1","de_b2c_1","de_b2c_2","dk_b2c_1","fi_b2c_1","fr_b2b_1","fr_b2c_1","it_b2c_1","nl_b2b_1","nl_b2c_1","nl_b2c_2","no_b2c_1","se_b2c_1"]}],"tax_class":[{"locale":null,"scope":null,"data":"HIGH"}],"kan_zigzag":[{"locale":null,"scope":null,"data":false}],"config_name":[{"locale":"fi_FI","scope":"haagplanten_net","data":"delokm8_fi2"}],"description":[{"locale":"da_DK","scope":"haagplanten_net","data":"danish desc"}],"uit_koelcel":[{"locale":null,"scope":null,"data":false}],"inbraakwerend":[{"locale":null,"scope":null,"data":false}],"vruchtdragend":[{"locale":null,"scope":null,"data":false}],"config_url_key":[{"locale":"da_DK","scope":"haagplanten_net","data":"test223456"},{"locale":"fi_FI","scope":"haagplanten_net","data":"test"}],"shipment_points":[{"locale":null,"scope":null,"data":"3.0000"}],"meta_description":[{"locale":"fi_FI","scope":"haagplanten_net","data":"test_fi(md)"}],"kan_vakbeplanting":[{"locale":null,"scope":null,"data":false}],"hidemetercalculator":[{"locale":null,"scope":null,"data":false}],"config_metadescription":[{"locale":"fi_FI","scope":"haagplanten_net","data":"test_fi(cmd)"}],"shipment_transport_type":[{"locale":null,"scope":null,"data":["PALLET"]}],"shipment_transport_height":[{"locale":null,"scope":null,"data":"GREATER_THAN_X"}],"shipment_availability_from":[{"locale":null,"scope":null,"data":"1"}],"shipment_availability_till":[{"locale":null,"scope":null,"data":"49"}]},"created":"2021-02-15T11:08:05+00:00","updated":"2021-02-15T11:08:05+00:00","associations":{}}';

        $data = "{\"identifier\":\"delokm889\",\"family\":\"Planten\",\"categories\":[\"Sierui_Allium\"],\"values\":{\"name\":
        [{\"locale\":\"nl_NL\",\"scope\":\"haagplanten_net\",\"data\":\"delokm8dutch2_16\"}],\"websites\":
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
            $retry, 'rest/v1/products/delokm889', 'PATCH', $data, "Content-type: application/json");
        $response->getStatusCode();
    }
}