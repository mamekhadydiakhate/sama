<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $repo;
private $encoder;
public function __construct(UserPasswordEncoderInterface $encoder ) 
    {
    $this->encoder=$encoder;
    }      
    public function load(ObjectManager $manager)
    {


  
    $faker = Factory::create('fr_FR');
    
        $profils =["CORP" ,"ODC" ,"REX" ,"PP" ,"FS" ,"RSEP"];
    foreach ($profils as $key => $libelle) 
    {
        $profil =new Profil() ;
        $profil ->setLibelle ($libelle );
        $manager ->persist ($profil );
        $manager ->flush();
   for ($i=0; $i <=1; $i++)
    {
        $user = new User() ;
        $user ->setProfil ($profil )
              ->setPrenom($faker->firstName)
              ->setNom($faker->lastName)
              ->setEmail($faker->email)
              ->setUsername($faker->name)
              ->setMatricule($faker->ean8);
        //Génération des Users
        $password = $this ->encoder ->encodePassword ($user, 'pass_1234' );
        $user ->setPassword ($password );
        $manager ->persist ($user);
    }
$manager ->flush();
}
    }
}  
