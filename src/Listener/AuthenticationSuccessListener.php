<?php
namespace App\Listener;

use App\Entity\User;
use DateTime;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use App\Entity\DetailsGrille;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $em;
	
	protected $request;
	protected $logger;
    /**
     * @var integer
     */
    protected $duration;
	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param ContainerInterface $container
	 */
	public function __construct($em,ContainerInterface $container,RequestStack $requestStack,LoggerInterface $logger) {
		$this->em = $em;
		$this->container=$container;
		$this->request=$requestStack->getCurrentRequest();
		$this->logger=$logger;
	}
	
	/**
	 * @param AuthenticationSuccessEvent $event
	 */
	public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
	{
        $this->duration = microtime(true);
		$message="Authentification";
        $this->container->get('monolog.logger.trace')->log(Logger::INFO, $message, array('container' => $this->container, 'event' => 'REQUEST'));
        $this->request->request->replace(["libelle"=>$message]);
        $this->logger->debug($message);
		$data = $event->getData();
		$user = $event->getUser();
		$user->setLoginTentative(0);
        $requestedPassword=$user?$user->getPasswordRequestedAt():null;
        $now=new DateTime(date("Y-m-d"));
		$interval=$requestedPassword?($now->diff($requestedPassword))->format('%a'):0;
		if($interval > 90){
			$data=array("code"=>504,"status"=>false,"message"=>"votre mot de pass a expiré, veuillez le changer");
		}
		else{
			$this->em->persist($user);
			$this->em->flush();
			if (!$user instanceof UserInterface) {
				return;
			}
            $apiHost = getenv('API_HOST');
            $data['success'] = true;
			$data['data'] = array(
					'id'			=> $user->getId(),
					'username' 		=> $user->getUsername(),
					'email'  		=> $user->getEmail(),
					'role'    		=> $user->getRoles(),
					'isConnected'   =>$user->getPasswordRequestedAt()?true:false
			);
		}
		$this->duration = (microtime(true) - $this->duration)*1000;
		$this->container->get('monolog.logger.trace')->log(Logger::INFO, $message, array(
			'container' => $this->container, 'event' => 'RESPONSE', 'response' => $event->getResponse(), 'duration' =>$this->duration
		));
		$event->setData($data);
	}
	
}
