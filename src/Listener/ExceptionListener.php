<?php
namespace App\Listener;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener {
	
	/**
	 * @var string
	 */
	private $env;
	
	/**
	 * @var boolean
	 */
	private $debug;
	
	public function __construct($env, $debug,LoggerInterface $logger) {
		$this->env = $env;
		$this->debug = $debug;
		$this->logger=$logger;
	}
    
    public function onKernelException(GetResponseForExceptionEvent $event,$original = true) {
    	if(!$this->debug) {
	        $exception = $event->getException();
	        $response = new JsonResponse();
	        $response->setContent(json_encode(array('code'=>$exception->getCode(), 'message'=>$exception->getMessage())));
	        $event->setResponse($response);
		}
		$exception= new Exception();
		$isCritical = !$exception instanceof HttpExceptionInterface || $exception->getStatusCode() >= 500;
        $context = ['exception' => $exception];
        if (null !== $this->logger) {
           /*  if ($isCritical) {
                $this->logger->critical($exception->getMessage(), $context);
            } else {
                $this->logger->error($exception->getMessage(), $context);
            } */
        } elseif (!$original || $isCritical) {
            error_log($exception->getMessage());
        }
    }
}
