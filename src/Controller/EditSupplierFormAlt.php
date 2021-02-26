<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\ChooseSupplier;
use App\Form\DeleteSupplier;
use App\Form\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Form\EditSupplier;

class EditSupplierFormAlt extends AbstractController
{
    /**
     * @Route("/editsupplieralt")
     */
    public function newAction(Request $request)
    {
        $supplier = null;
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_USER');
        $SupRepository = $this->getDoctrine()->getRepository(Supplier::class);
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(EditSupplier::class)->handleRequest($request); //all suppliers attributes
        $form2 = $this->createForm(ChooseSupplier::class)->handleRequest($request); //choose
        $form3 = $this->createForm(DeleteSupplier::class)->handleRequest($request); //Delete Supplier
        $form4 = $this->createForm(Result::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['new']->getData()) {
                $supplier = new Supplier();
                if ($SupRepository->findOneBy(['name' => $form['label']->getData()])) {
                    $form4->get('result')->setData(date("H:i:s", time()) . ' WRONG NAME');
                    return $this->render('form/editsupplier.html.twig', [
                        'form3' => $form3->createView(),
                        'form2' => $form2->createView(), 'form' => $form->createView(),
                        'form4' => $form4->createView(),
                    ]);
                }
            } else if ($form['name']->getData()) {
                $supplier = $SupRepository->findOneBy(['name' => $form['name']->getData()]);
            }
            if ($supplier) {
                $supplier->setName($form['label']->getData());
                $supplier->setPassword($form['password']->getData());
                $supplier->setApiUrl($form['api_url']->getData());
                $supplier->setTokenUrl($form['token_url']->getData());
                $supplier->setOauth2Key($form['oauth2_key']->getData());
                $supplier->setDelay($form['delay']->getData());
                $supplier->setUser($form['login']->getData());
                $supplier->setGrantType($form['grant_type']->getData());
                $entityManager->persist($supplier);
                $entityManager->flush();
                $form4->get('result')->setData(date("H:i:s", time()) . ' SUCESS');
            } else {
                $form4->get('result')->setData(date("H:i:s", time()) . ' NO SUPPLIER!');
            }
        }

        if ($form2->isSubmitted() && $form2->isValid()) {
            if (!$form2['supplier']->getData()) {
                $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
            } else {
                $supplier = $SupRepository->findOneBy(['name' => $form2['supplier']->getData()->getName()]);
                $form->get('name')->setData($supplier->getName());
                $form->get('label')->setData($supplier->getName());
                $form->get('api_url')->setData($supplier->getApiUrl());
                $form->get('token_url')->setData($supplier->getTokenUrl());
                $form->get('oauth2_key')->setData($supplier->getOauth2Key());
                $form->get('delay')->setData($supplier->getDelay());
                $form->get('login')->setData($supplier->getUser());
                $form->get('password')->setData($supplier->getPassword());
                $form->get('grant_type')->setData($supplier->getGrantType());
                $form->get('new')->setData(FALSE);
                $form4->get('result')->setData(date("H:i:s", time()) . ' READY');
            }
        }

        if ($form3->isSubmitted() && $form3->isValid()) {
            try {
                if (!$form3['supplier']->getData()) {
                    $form4->get('result')->setData('ERROR');
                } else {
                    $supplier = $SupRepository->findOneBy(['name' => $form3['supplier']->getData()->getName()]);
                    $entityManager->remove($supplier);
                    $entityManager->flush();
                    $form->get('new')->setData(FALSE);
                    $form4->get('result')->setData(date("H:i:s", time()) . ' SUCESS');
                }
            } catch (\Exception $e) {
                $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
            }
        }

        return $this->render('alt/editsupplier_alt.html.twig', [
            'form3' => $form3->createView(),
            'form2' => $form2->createView(), 'form' => $form->createView(),
            'form4' => $form4->createView(),
        ]);
    }
}
