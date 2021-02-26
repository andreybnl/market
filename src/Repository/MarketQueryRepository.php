<?php

namespace App\Repository;

use App\Entity\MarketQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MarketQuery|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketQuery|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketQuery[]    findAll()
 * @method MarketQuery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketQueryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketQuery::class);
    }

    // /**
    //  * @return MarketQuery[] Returns an array of MarketQuery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MarketQuery
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
