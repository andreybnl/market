<?php

namespace App\Repository;

use App\Entity\SpeedChannels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpeedChannels|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpeedChannels|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpeedChannels[]    findAll()
 * @method SpeedChannels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpeedChannelsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpeedChannels::class);
    }

    // /**
    //  * @return SpeedChannels[] Returns an array of SpeedChannels objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpeedChannels
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
