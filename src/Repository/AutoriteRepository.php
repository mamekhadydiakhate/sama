<?php

namespace App\Repository;

use App\Entity\Autorite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Autorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autorite[]    findAll()
 * @method Autorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autorite::class);
    }

    // /**
    //  * @return Autorite[] Returns an array of Autorite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Autorite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
