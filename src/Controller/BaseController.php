<?php
namespace App\Controller;
use App\Mapping\UserMapping;
use App\Model\AgenceManager;
use App\Model\DemandeManager;
use App\Model\InterimaireManager;
use App\Model\ObjectifManager;
use App\Model\ProfilManager;
use App\Model\StructureManager;
use App\Model\UserManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BaseController  extends FOSRestController implements ClassResourceInterface{
    /**
     * @param JWTTokenManagerInterface $jwtM
     */
    protected $em;
    protected $validator;
    protected $tokenStorage;
    protected $mailer;
    public function __construct(JWTTokenManagerInterface $jwtManager,\Swift_Mailer $mailer,TokenStorageInterface $tokenStorage,EntityManagerInterface $em,ValidatorInterface $validator)
    {
        $this->jwtM = $jwtManager;
        $this->tokenStorage=$tokenStorage;
        $this->validator=$validator;
        $this->mailer=$mailer;
        $this->em=$em;
    }
    public function sendMail($user,$bcc){
        $message = (new \Swift_Message())
            ->setFrom(array('no-reply@orange-sonatel.com'=>'API GDI'))
            ->setTo($user->getEmail())
            ->setBcc($bcc)
            ->setSubject("Données de connexion")
         //   ->attach(\Swift_Attachment::fromPath($chemin))
            ->setBody('Login: '.$user->getEmail().' Password: '.$user->getPassword());
        $this->mailer->send($message);
    }
    public function limitOffset($page){
        $limit=null;
        $offset=null;
        if ($page==0){
            $limit=10;
            $offset=0;
        }elseif ($page==1){
            $limit=$_ENV["ITEMS_PER_PAGE"];
            $offset=0;
        }elseif ($page>1){
            $limit=$_ENV["ITEMS_PER_PAGE"];
            $offset=($page-1)*$_ENV["ITEMS_PER_PAGE"];
        }
        return array("limit"=>$limit,"offset"=>$offset,"page"=>$page);
    }
}