<?php

namespace App\Command;

use App\Model\Condition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\MarketProductRepository;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;

class MarketSaveBase
{
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
    }

    public function connect($defaultName, $supplierName, $retry, $query, $requiredFieldsArray)
    {
        try {
            if ($this->condition->checkBusy()) {
                throw new \Exception('Another process not terminated');
            } else {
                $this->condition->createBusy();
                $token = $this->connector->getToken($supplierName);
                $startTime = new \DateTime('now');
                $response = $this->connector->getContent($token, $supplierName, $retry,
                    $query);
                $endTime = new \DateTime('now');
                $importResult = $this->storeMarketToDb($response, $requiredFieldsArray);
                $this->taskLogger->TaskLogAdd($defaultName, $startTime, strlen($response->getContent()), $endTime,
                    (integer)$startTime->diff($endTime)->format("%f"), $response->getStatusCode(),
                    $importResult[0], $importResult[0], $importResult[1]);
            }
        } catch (\Exception $e) {
            $this->condition->deleteBusy();
            $endTime = new \DateTime('now');
            $this->taskLogger->TaskLogAdd($defaultName, $startTime, 0, $endTime,
                (integer)$startTime->diff($endTime)->format("%f"), $e->getMessage(),
                0, 0, 0);
            return Command::FAILURE;
        }
        $this->condition->deleteBusy();
        return Command::SUCCESS;
    }

    private function storeMarketToDb($response, $requiredFieldsArray)
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
                    foreach ($requiredFieldsArray as $value) {
                        $setterValue = 'set' . ucfirst($value);
                        if ($value == 'category' && isset($result[$value])) {
                            $categoryCode = array_key_first($result[$value]);
                            $formattedValue = $categoryCode . '=>' . array_shift($result[$value])['name'];
                            $product->$setterValue($formattedValue);
                        } else {
                            $product->$setterValue(isset($result[$value]) ? $result[$value] : '0');
                        }
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