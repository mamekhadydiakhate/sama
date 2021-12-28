<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Annotation\QMLogger;
use App\Model\HistoriqueManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use ApiPlatform\Core\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HistoriqueController extends BaseController{
    private $historiqueManager;
    public function __construct(HistoriqueManager $historiqueManager,JWTTokenManagerInterface $jwtManager, \Swift_Mailer $mailer, TokenStorageInterface $tokenStorage, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->historiqueManager=$historiqueManager;
        parent::__construct($jwtManager, $mailer, $tokenStorage, $em, $validator);
    }

    /**
     * @Rest\Get("/userhistorique/{id}")
     * @QMLogger(message="historiques d'un utilisateur")
     */
    public function getUserHistoriques($id,Request $request){
        $page=$request->query->get('page',1);
        return new JsonResponse($this->historiqueManager->getUserHistoriques($id,$page));
    }
    /**
     * @Get("/historiques")
     * @QMLogger(message="Toutes les historiques") 
     */
    public function getAllHistoriques(Request $request){
        $page=$request->query->get('page',1);
        return new JsonResponse($this->historiqueManager->getAllHistoriques($page));
    }

    /**
     * @Post("/historiquesDates/{id}")
     * @QMLogger(message="Toutes les historiques entre 2 dates")
     */
    public function gethistoriquesEntre(Request $request,$id){
        $page=$request->query->get('page',1);
        $data=json_decode($request->getContent(),true);
        return new JsonResponse($this->historiqueManager->historiqueBetween($page,$id,$data));
    }
}
