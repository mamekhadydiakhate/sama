<?php
namespace App\Listener;

use DateTime;
use App\Entity\User;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class AuthenticationFailureListener
{
	/**
	 * @param \Doctrine\ORM\EntityManager $em
     * @param RequestStack $requestStack
     * @param JWTEncoderInterface  $JWTEncoder
     * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param ContainerInterface $container
	 */
    
    /**
     * @var integer
     */
    protected $duration;
	public function __construct($em,ContainerInterface $container, RequestStack $requestStack,JWTEncoderInterface  $JWTEncoder,UserPasswordEncoderInterface $passwordEncoder) {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->JWTEncoder=$JWTEncoder;
        $this->passwordEncoder=$passwordEncoder;
		$this->container=$container;
    
	}
	
	/**
	 * @param AuthenticationFailureEvent $event
	 */
    
	public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
	{

        $this->duration = microtime(true);
		$message="Authentification";
        $this->container->get('monolog.logger.trace')->log(Logger::INFO, $message, array('container' => $this->container, 'event' => 'REQUEST'));
        $request= $this->requestStack->getCurrentRequest();
        $post=json_decode($request->getContent(),true);
        $username=$post["username"];
        $password=$post["password"];
        $user=$this->em->getRepository(User::class)->findOneByUsername($username);
        $requestedPassword=$user?$user->getPasswordRequestedAt():null;
        $now=new DateTime(date("Y-m-d"));
        $interval=$requestedPassword?($now->diff($requestedPassword))->format('%a'):0;
        $now=strtotime(date("Y-m-d H:i:s"));
        if(!$user){
            $result =array("code"=>503,"status"=>false,"message"=>"Nom d'utilisateur invalide");
            $event->setResponse(new JsonResponse($result));
        }
        else{
            $login=$user->getLoginTentative()?$user->getLoginTentative():0;
            $last_login=$user->getLoginTentativeAt()?strtotime(($user->getLoginTentativeAt())->format("Y-m-d H:i:s")):null;
          /*   if($interval > 90){
                $response= new JsonResponse(array("code"=>504,"status"=>false,"message"=>"votre mot de pass a expiré, veuillez le changer"));
            }
            else */ if($login >= 3 && (($now - $last_login)/60) <=1){
                $user->setEnabled(0);
                $this->em->persist($user);
                $result =array("code"=>503,"status"=>false,"message"=>"votre compte est bloqué suite à trois tentatives de connexions sans succés");
            }else if($login >= 3 && (($now - $last_login)/60) >=2){
                $user->setEnabled(1);
                $user->setLoginTentative(0);
                $this->em->persist($user);
                $result= $this->login($username,$password,$post);
            }else{
                $user->setEnabled(1);
                $user->setLoginTentativeAt(new DateTime(date("Y-m-d H:i:s")));
                $login++;
                $user->setLoginTentative($login);
                $this->em->persist($user);
                $result=$this->loginLdap($post,$user);
            }
            $this->em->flush();
            $this->duration = (microtime(true) - $this->duration)*1000;
            $this->container->get('monolog.logger.trace')->log(Logger::INFO, $message, array(
                'container' => $this->container, 'event' => 'RESPONSE', 'response' => $event->getResponse(), 'duration' => $this->duration
            ));
        $event->setResponse(new JsonResponse($result));
        }
    }
    
    public function login($username,$password,$post)
    {

        $repo = $this->em->getRepository(User::class);
            $user = $repo-> findOneBy(['username' => $username]);
            if(!$user){
               return [
                    'code' => 'ko',
                    'message' => 'Username incorrect'
                ];
            }
            $isValid = $this->passwordEncoder->isPasswordValid($user, $password);
           // var_dump(1);
            if($isValid){
              //  var_dump(2);
                $token = $this->JWTEncoder->encode([
                    'username' => $user->getUsername(),
                    'roles'=>$user->getRoles(),
                    'exp' => time() + 86400 // 1 day expiration
                ]);
                $data = array(
                    'id'			=> $user->getId(),
                    'username' 		=> $user->getUsername(),
                    'email'  		=> $user->getEmail(),
                    'role'    		=> $user->getRoles()
                            );
                return [
                    'token' => $token,
                    'data'=>$data
                ];
        }
        else {
            return $this->loginLdap($post,$user);
        }
    }

    public function loginLdap($post,$user){
        $ldap = Ldap::create('ext_ldap', ['host' => '10.100.55.80', 'port' => '389', 'encryption' => 'none', 'version' => 3, 'referrals' => false]);
        try {
            $ldap->bind(sprintf('%s@orange-sonatel.com', $post['username']), $post['password']);
            $query = $ldap->query('dc=orange-sonatel,dc=com', '(&(samaccountname='.$post['username'].'))');
            $result = $query->execute()->toArray();
            $value=null;
            foreach($result as $entry) {
                if($entry->getAttributes()) {
                    $value = $entry->getAttributes();
                    break;
                }
            }
            if(!$user){
                $result= array('success' => "false",'code'=>502,'message'=>'utilisateur inexistant');
            }
            else{
                $token = $this->JWTEncoder->encode([
                    'username' => $value['sAMAccountName'][0],
                    'roles'=>$user->getRoles(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);
                $result = array('token' => $token,'success' => true, 'data' => array(
                            'prenom' => $value['givenName'][0], 'nom' => $value['sn'][0], 'email' => $value['mail'][0], 'username' => $value['sAMAccountName'][0],
                            'matricule' => isset($value['initials'][0]) ? $value['initials'][0] : null,
                            'telephone' => isset($value['telephoneNumber'][0]) ? $value['telephoneNumber'][0] : null, 
                            'structure' => isset($value['description'][0]) ? $value['description'][0] : '',
                            'roles'=>$user->getRoles(),
                            'isConnected'   =>$user->getPasswordRequestedAt()?true:false

                ));
                }
        } catch(ConnectionException $e) {
            $result = array('success' => "false",'code'=>502,'message'=>'login ou mot de pass incorrect');
        }
        return $result;
    }
	
}
