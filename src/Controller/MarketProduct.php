<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MarketProduct extends AbstractController
{
    /**
     * @Route("/showmarketalt", name="showmarketalt")
     *
     */
    public function showCron(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        $product_repository = $this->getDoctrine()->getRepository(\App\Entity\MarketProduct::class);
        $product = $product_repository->findBy(array(),array('id'=>'ASC'),1000,0);

        return $this->render('alt/marketProduct_alt.html.twig',
            array('product' => $product));
    }
}