<?php


namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Annotation\QMLogger;
use App\Model\ProfilManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfilController extends BaseController
{
    private $profilManager;
    public function __construct(ProfilManager $profilManager,JWTTokenManagerInterface $jwtManager, \Swift_Mailer $mailer, TokenStorageInterface $tokenStorage, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->profilManager=$profilManager;
        parent::__construct($jwtManager, $mailer, $tokenStorage, $em, $validator);
    }

    /**
     * @Rest\Post("admin/profil")
     * @QMLogger(message="Ajout profil")
     */
    public function ajouterProfil(Request $request){
        $data=json_decode($request->getContent(),true);
        return new JsonResponse($this->profilManager->addProfil($data));
    }

    /**
     * @Rest\Get("/profils")
     * @QMLogger(message="Liste profil")
     */
    public function listProfil(){
        return new JsonResponse($this->profilManager->listProfil());
    }


    /**
     * @Rest\Delete("admin/profil/{id}")
     * @QMLogger(message="Supprimer profil")
     */
    public function deleteProfil($id){
        return new JsonResponse($this->profilManager->deleteProfil($id));
    }

    /**
     * @Rest\Put("admin/profil/{id}")
     * @QMLogger(message="Modifier profil")
     */
    public function modifierProfil($id,Request $request){
        $data=json_decode($request->getContent(),true);
        return new JsonResponse($this->profilManager->updateProfil($data,$id));
    }

}