<?php
namespace App\Service;

use App\Entity\Action;

class ConnectedUserService {
    public static function getConnectedUser($token,$em){
        $p=new \ReflectionProperty('Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken','rawToken');
        $p->setAccessible(true);
        $token=$p->getValue($token->getToken());
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        return $em->findOneBy(["username"=>$jwtPayload->username]);
    }
}
