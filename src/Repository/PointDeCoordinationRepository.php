<?php

namespace App\Repository;

use App\Entity\PointDeCoordination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PointDeCoordination|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointDeCoordination|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointDeCoordination[]    findAll()
 * @method PointDeCoordination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointDeCoordinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PointDeCoordination::class);
    }

    // /**
    //  * @return PointDeCoordination[] Returns an array of PointDeCoordination objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PointDeCoordination
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
