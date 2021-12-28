<?php
namespace App\Service;
 use App\Entity\User;
 use App\Repository\UserRepository;
 use phpDocumentor\Reflection\Types\Self_;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

 class AccessControlService extends BaseService {
     public static function checkRole($roles,$token,$em){
         $p=new \ReflectionProperty('Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken','rawToken');
         $p->setAccessible(true);
         $token=$p->getValue($token->getToken());
         $tokenParts = explode(".", $token);
         $tokenHeader = base64_decode($tokenParts[0]);
         $tokenPayload = base64_decode($tokenParts[1]);
         $jwtHeader = json_decode($tokenHeader);
         $jwtPayload = json_decode($tokenPayload);
         $user=$em->findOneBy(["username"=>$jwtPayload->username]);
        if ($user){
             if($user->getProfil()){
             if(!in_array($user->getProfil()->getLibelle(),$roles)){
                return array("code"=>403,"success"=>true,"data"=>"Vous n'avez pas acces a cette ressource!");
            }
            }
             else{
                 return array("code"=>403,"success"=>true,"data"=>"Vous n'avez pas acces a cette ressource!");

             }
        }
     }
 }
