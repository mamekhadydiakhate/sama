<?php

namespace App\Repository;

use App\Entity\TypePeriodicite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypePeriodicite|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePeriodicite|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePeriodicite[]    findAll()
 * @method TypePeriodicite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePeriodiciteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypePeriodicite::class);
    }

    // /**
    //  * @return TypePeriodicite[] Returns an array of TypePeriodicite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypePeriodicite
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
