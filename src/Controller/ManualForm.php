<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\ManualRequest;
use App\Form\RequestResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\SupplierRepository;
use App\Model\Connector;
use App\Model\TaskLogger;

class ManualForm extends AbstractController
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
     * @Route("/manualform", name = "manual_form")
     */
    public function form(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');

        $form = $this->createForm(ManualRequest::class);  //already exist! just use!
        $form->handleRequest($request);

        $form3 = $this->createForm(RequestResult::class);

        if ($form->isSubmitted() && $form->isValid() && $form->getClickedButton() === $form->get('save')) {
            if (!$form['supplier']->getData()) {
                $form3->get('result')->setData(date("H:i:s", time()) . ' NO SUPPLIER!');
                return $this->render('default/manual.html.twig', array(
                    'form' => $form->createView(), 'form3' => $form3->createView()
                ));
            }
            $supplier = $this->getDoctrine()->getRepository(Supplier::class)->findOneBy(['name' => $form['supplier']->getData()->getName()]);

            $accessToken = $this->connector->getToken($form['supplier']->getData()->getName());
            $response = $this->connector->getContent($accessToken, $form['supplier']->getData()->getName(),
                3, $form['Request']->getData(), $form['TYPE']->getData(), $form['body']->getData());
            try {
                if ($response->getStatusCode() == '200') {                      //error when ip for MArket not in whitelist!
                    $responce_to_array = json_decode($response->getContent());
                    $form3->get('answer')->setData(
                        json_encode($responce_to_array, JSON_PRETTY_PRINT)
                    );
                    $form3->get('result')->setData(date("H:i:s", time()) . ' SUCESS! ' .
                        $supplier->getApiUrl() . $form['Request']->getData());
                    $this->taskLogger->ManualLog($form['supplier']->getData()->getName() . ' ' . $form['Request']->getData(), $response->getStatusCode(),
                        $response->getContent(), $form['body']->getData());
                } else {
                    $form3->get('result')->setData(date("H:i:s", time()) . ' ' . $response->getStatusCode() .
                        ' ' . $supplier->getApiUrl() . $form['Request']->getData());
                    $this->taskLogger->ManualLog($form['supplier']->getData()->getName() . ' ' . $form['Request']->getData(), $response->getStatusCode(),
                        NULL, $form['body']->getData());
                }
            } catch (\Exception $exception) {
                $form3->get('result')->setData(date("H:i:s", time()) . ' IP ERROR! ' .
                    $supplier->getApiUrl() . $form['Request']->getData());
            }
        } else {
            $form3->get('result')->setData(date("H:i:s", time()) . ' UNSUBMITTED');
        }

        return $this->render('default/manual.html.twig', array(
            'form' => $form->createView(), 'form3' => $form3->createView()
        ));
    }
}