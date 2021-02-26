<?php

namespace App\Repository;

use App\Entity\Crontask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Crontask|null find($id, $lockMode = null, $lockVersion = null)
 * @method Crontask|null findOneBy(array $criteria, array $orderBy = null)
 * @method Crontask[]    findAll()
 * @method Crontask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrontaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crontask::class);
    }

    // /**
    //  * @return Crontask[] Returns an array of Crontask objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Crontask
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
