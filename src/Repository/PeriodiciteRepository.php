<?php

namespace App\Repository;

use App\Entity\Periodicite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Periodicite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periodicite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periodicite[]    findAll()
 * @method Periodicite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodiciteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Periodicite::class);
    }

    // /**
    //  * @return Periodicite[] Returns an array of Periodicite objects
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
    public function findOneBySomeField($value): ?Periodicite
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
