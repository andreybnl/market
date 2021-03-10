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

class MarketSaveStock extends Command
{
    protected static $defaultName = 'cron_prepared:market_product_stock_save';

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
        try {
            if ($this->condition->checkBusy()) {
                throw new \Exception('Another process not terminated');
            } else {
                $this->condition->createBusy();
                $token = $this->connector->getToken('PlantsMarket');
                $startTime = new \DateTime('now');
                $response = $this->connector->getContent($token, 'PlantsMarket', $input->getOption('retry'),
                    'v1/channel/' . $channel . '/product/stock');
                $endTime = new \DateTime('now');
                $this->storeMarketToDb($response);
            }
        } catch (\Exception $e) {
            $this->condition->deleteBusy();
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
                        break;
                    }
                    //else if ($product->getEditTime() === $result['editTime']) {
                    //    continue; } //producr change detected without EditDAta change!

                    $product->setCreateTime(isset($result['createTime']) ? $result['createTime'] : '0');
                    $product->setSku($result['sku']);
                    $product->setBtchStock(isset($result['btch_stock']) ? $result['btch_stock'] : '0');

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