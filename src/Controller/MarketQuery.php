<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Entity\MarketQuery as MQ;
use App\Form\MarketManualRequest;
use App\Form\MarketQueryRequest;
use App\Form\RequestResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;

class MarketQuery extends AbstractController
{
    private $taskLogger;
    private $connector;
    private $client;
    private $supplierRepository;

    public function __construct(
        TaskLogger $taskLogger,
        Connector $connector,
        HttpClientInterface $client,
        SupplierRepository $supplierRepository
    )
    {
        $this->taskLogger = $taskLogger;
        $this->connector = $connector;
        $this->client = $client;
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @Route("/marketform", name = "market_form")
     */
    public function form(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $form = $this->createForm(MarketManualRequest::class)->handleRequest($request);
        $form2 = $this->createForm(MarketQueryRequest::class)->handleRequest($request);
        $form3 = $this->createForm(RequestResult::class);

        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton() === $form->get('send')) {
            $supplier = $this->getDoctrine()->getRepository(Supplier::class)->findOneBy(['name' => $form['supplier']->getData()->getName()]);
            $accessToken = $this->connector->getToken($form['supplier']->getData()->getName());
            $response = $this->connector->getContent($accessToken, $form['supplier']->getData()->getName(),
                3, $form['Request']->getData(), $form['TYPE']->getData());
            try {
                if ($response->getStatusCode() == '200') {                      //error when ip for Market not in whitelist!
                    $responce_to_array = json_decode($response->getContent());
                    $form3->get('answer')->setData(
                        json_encode($responce_to_array, JSON_PRETTY_PRINT)
                    );
                    $form3->get('result')->setData(date("H:i:s", time()) . ' SUCCESS! ' .
                        $supplier->getApiUrl() . $form['Request']->getData());
                    $this->taskLogger->ManualLog($form['supplier']->getData()->getName() . ' ' . $form['Request']->getData(), $response->getStatusCode(),
                        $response->getContent());
                } else {
                    $form3->get('result')->setData(date("H:i:s", time()) . ' ' . $response->getStatusCode() .
                        ' ' . $supplier->getApiUrl() . $form['Request']->getData());
                    $this->taskLogger->ManualLog($form['supplier']->getData()->getName() . ' ' . $form['Request']->getData(), $response->getStatusCode(),
                        NULL);
                }
            } catch (\Exception $exception) {
                $form3->get('result')->setData(date("H:i:s", time()) . ' ERROR! ' .
                    $supplier->getApiUrl() . $form['Request']->getData());
            }
        }

        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton() === $form->get('save')) {
            $supplier = $this->getDoctrine()->getRepository(Supplier::class)->findOneBy(['name' => $form['supplier']->getData()->getName()]);
            $entityManager = $this->getDoctrine()->getManager();
            $query = new MQ();
            $query->setQuery($form['Request']->getData());
            $label = $form['label']->getData();
            $query->setLabel(isset($label) ? $label : $form['supplier']->getData()->getName() . ' ' . $form['Request']->getData());
            $query->setRequestType($form['TYPE']->getData());
            $query->setSupplier($form['supplier']->getData()->getName());
            $entityManager->persist($query);
            $entityManager->flush();
            $form3->get('result')->setData(date("H:i:s", time()) . ' Query added! ' .
                $supplier->getApiUrl() . $form['Request']->getData());
        }

        if ($form2->isSubmitted() && $form2->isValid() && $form2->getClickedButton() === $form2->get('delete')) {
            $user = $this->getUser();
            $roles = $user->getRoles();
            if ($roles[0] == 'ROLE_ADMIN') {
                //check user group
                $entityManager = $this->getDoctrine()->getManager();
                $QueryRepository = $this->getDoctrine()->getRepository(MQ::class);
                $query = $QueryRepository->findOneBy(['label' => $form2['query']->getData()->getLabel()]);
                $entityManager->remove($query);
                $entityManager->flush();
            }
            else{
                $form3->get('result')->setData('You have not permission!');
            }
        }

        if ($form2->isSubmitted() && $form2->isValid() && $form2->getClickedButton() === $form2->get('run')) {
            $QueryRepository = $this->getDoctrine()->getRepository(MQ::class);
            $query = $QueryRepository->findOneBy(['label' => $form2['query']->getData()->getLabel()]);
            //set request in field
            $form->get('Request')->setData($query->getQuery());
            $form->get('TYPE')->setData($query->getRequestType());
            $form->get('supplier')->setData($this->getDoctrine()->getRepository(Supplier::class)->findOneBy(['name' => $query->getSupplier()]));

            $accessToken = $this->connector->getToken($query->getSupplier());
            $response = $this->connector->getContent($accessToken, $query->getSupplier(),
                3, $query->getQuery(), $query->getRequestType());

            try {
                if ($response->getStatusCode() == '200') {                      //error when ip for Market not in whitelist!
                    $responce_to_array = json_decode($response->getContent());
                    $form3->get('answer')->setData(
                        json_encode($responce_to_array, JSON_PRETTY_PRINT)
                    );
                    $form3->get('result')->setData(date("H:i:s", time()) . ' SUCCESS! ');
                    $this->taskLogger->ManualLog($query->getSupplier() . ' ' . $query->getQuery(), $response->getStatusCode(),
                        $response->getContent());
                } else {
                    $form3->get('result')->setData(date("H:i:s", time()) . ' ' . $response->getStatusCode());
                    $this->taskLogger->ManualLog($query->getSupplier() . ' ' . $query->getQuery(), $response->getStatusCode(),
                        NULL);
                }
            } catch (\Exception $exception) {
                $form3->get('result')->setData(date("H:i:s", time()) . ' ERROR! ');
            }
        }

        return $this->render('default/marketQuery.html.twig', array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView()
        ));
    }

}