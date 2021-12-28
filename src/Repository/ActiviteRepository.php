<?php

namespace App\Repository;

use DateTime;
use App\Entity\Activite;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Activite|null find($id, $semaine, $lockMode = null, $lockVersion = null)
 * @method Activite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activite[]    findAll()
 * @method Activite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activite::class);
    }

    // /**
    //  * @return Activite[] Returns an array of Activite objects
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

    
    public function precede($semaine ,$year)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.semaine = :semaine')
            ->setParameter('semaine', $semaine)
            ->andWhere('a.date BETWEEN :dateDebut AND :dateFin')
            ->setParameter('dateDebut',new DateTime("$year-01-01"))
            ->setParameter('dateFin',new DateTime("$year-12-31"))
            ->getQuery()
            ->getResult()
            
        ;
    }
    
}
