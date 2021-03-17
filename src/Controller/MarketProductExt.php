<?php

namespace App\Controller;

use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;

class MarketProductExt extends AbstractController
{
    /**
     * @Route("/showmarketext", name="showmarketext")
     *
     */
    public function showCron(Request $request, DataTableFactory $dataTableFactory)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ["label" => "ID", "searchable" => true,
                'className' => 'text-center'])
            ->add('sku', TextColumn::class, ["label" => "SKU", "searchable" => true, "globalSearchable" => true
                , 'render' => '<strong>%s</strong>'])
            ->add('name', TextColumn::class, ["label" => "NAME", "searchable" => true,
             //   'template' => 'tables/cell.html.twig'
            ])
            ->add('name_search', TextColumn::class, ["label" => "SEARCHNAME", "searchable" => true])
            ->add('chn_price_retail', TextColumn::class, ["label" => "price", "searchable" => true])
            ->add('btch_stock', TextColumn::class, ["label" => "stock", "searchable" => true])
            ->add('editTime', TextColumn::class, ["label" => "edit time", "searchable" => false])
            ->add('rtl_size_code', TextColumn::class, ["label" => "size", "searchable" => true])
            ->add('category', TextColumn::class, ["label" => "category", "searchable" => true])
            ->createAdapter(ORMAdapter::class, [
                'entity' => \App\Entity\MarketProduct::class,
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('alt/marketProduct_alt_ext.html.twig', ['datatable' => $table]);
    }
}