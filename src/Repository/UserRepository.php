<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }
        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function countUsers() {
        //here
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.enabled = :true')
            ->setParameter('true',true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function listUsers($page,$limit) {
        //here
        return $this->createQueryBuilder('u')
            ->select('u.id,u.prenom,u.nom,u.email,u.service,u.matricule,p.id as idProfil, p.libelle as profil,  u.enabled as etat')
            ->innerJoin('u.profil','p')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function searchUser($value){
        return $this->createQueryBuilder('u')
            ->select('u.id,u.prenom,u.nom,u.email,u.service,u.matricule')
            ->where('u.matricule LIKE :value')
            ->setParameter('value',"%$value%")
            ->getQuery()
            ->getResult();
    }

    public function listEmailUsers(){
        return $this->createQueryBuilder('u')
            ->select('u.id,u.email')
            ->getQuery()
            ->getResult();
    }

}
