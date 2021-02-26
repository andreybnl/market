<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Entity\Tasks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\SupplierRepository;
use App\Model\AlterCron;

class TestManualRequest extends AbstractController
{
    private $alterCron;
    private $client;
    private $supplierRepository;

    public function __construct(
        AlterCron $alterCron,
        HttpClientInterface $client,
        SupplierRepository $supplierRepository
    )
    {
        $this->alterCron = $alterCron;
        $this->client = $client;
        $this->supplierRepository = $supplierRepository;
    }

    //for test purposes only! Temporary!
    /**
     * @Route("/testmarket", name = "test_market")
     */
    public function form(Request $request)
    {
        $accessToken = null;
        $add = array(
            "body" => "grant_type=password&username=api@plantsonline&password=Q1!werty",
            'verify_peer' => false, 'verify_host' => false
        );

        $token_response = $this->client->request(
            'POST',
            'https://api.plant-market.net/token', $add);
        $token_response->getContent();
        $accessToken = json_decode($token_response->getContent(false))->access_token;
        if ($accessToken) {
            $headers = array(
                'auth_bearer' => $accessToken, 'verify_peer' => false, 'verify_host' => false
            );
            $response = $this->client->request(
                'GET', 'https://api.plant-market.net/v1/channel/configuration', $headers);
            $response->getContent();
        }
        return new Response(
            $response->getStatusCode());
    }


    /**
     * @Route("/cron_add")
     */
    //for test purposes only!
    public function newCronTask(Request $request, $job_c = 'cron:speed_sync-catalog')
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Task = new Tasks();
        $Task->setCode($job_c);
        $Task->setCreated(new \DateTime('now'));
        $entityManager->persist($Task);
        $entityManager->flush();
        return new Response('Job ' .$job_c . ' is created');
    }

    /**
     * @Route("/supplier_add", name="supplier_add")
     */
    public function createAccess(ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $enabled = false;
        $name = '';
        $grand_type = '';
        $oauth = '';
        if($enabled) {
            $entityManager = $this->getDoctrine()->getManager();
            $supplier = new Supplier();
            $supplier->setName('PlantsMarket')->setGrantType('password');
            $supplier->setOauth2Key(null);
            $supplier->setUser('api@plantsonline');
            $supplier->setPassword('Q1!werty');
            $supplier->setApiUrl('https://api.plant-market.net');
            $entityManager->persist($supplier);
            $entityManager->flush();
            return new Response('Saved new supplier with name ' . $supplier->getName());
        }
        return new Response('NOT ALLOWED!');
    }

    /**
     * @Route("/cronalt_add")
     */
    //for test purposes only!
    public function newAltCronTask()
    {
        $now = new \DateTime();
        $today = clone $now;
        $beforeYesterday = $now->modify('-2 days');
        $this->alterCron->addCron('one', 'debug:container', '--help', '@daily', 'one.log', 100, $beforeYesterday);
        $this->alterCron->addCron('two', 'debug:container', '', '@daily', 'two.log', 80, $beforeYesterday, true);
        $this->alterCron->addCron('three', 'debug:container', '', '@daily', 'three.log', 60, $today, false, true);
        $this->alterCron->addCron('four', 'debug:router', '', '@daily', 'four.log', 40, $today, false, false, true, -1);
        return new Response('SUCCESS!');
    }

}