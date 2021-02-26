<?php

namespace App\Repository;

use App\Entity\QuenyLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuenyLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuenyLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuenyLog[]    findAll()
 * @method QuenyLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuenyLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuenyLog::class);
    }

    // /**
    //  * @return QuenyLog[] Returns an array of QuenyLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuenyLog
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
