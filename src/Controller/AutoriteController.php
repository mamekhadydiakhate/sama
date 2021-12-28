<?php

namespace App\Controller;




use App\Entity\Autorite;
use App\Form\AutoriteType;
use App\Annotation\QMLogger;
use FOS\UserBundle\Mailer\Mailer;
use App\Controller\BaseController;
use App\Repository\AutoriteRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutoriteController extends BaseController
{
    private AutoriteRepository $autoriteRepo;

    public function __construct(AutoriteRepository $autoriteRepo)
    {
        $this->autoriteRepo = $autoriteRepo;
        $dateDebut = new DateTime();
        $serializer = new Serializer();
       
    }
    
    /**
     * @Post("/api/autorite", name="autorites")
     */
    public function addAutorite(Request $request ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $autorite = $serializer->deserialize($request->getContent(), autorite::class,'json');
        $errors = $validator->validate($autorite);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($autorite);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
      
    }

    /**
     * @Get("/api/autorite", name="autorite")
     */
    public function listAutorite(): Response
    {
        
        $autorites = $this->autoriteRepo->findAll();
        $response = $this->json($autorites, 200, [], ['groups' => 'autorite:read']);

        return $response; 
    }
      /**
     * @Get("/api/autorite/{id}")
     * @QMLogger(message="Details autorite")
     */
    public function detailsAutorite($id){
        $autorites = $this->autoriteRepo->find($id);
        $response = $this->json($autorites, 200, [], ['groups' => 'autorite:read']);

        return $response; 
        
    }
    
    /**
     * @Get("/api/rechercheautorite")
     * @QMLogger(message="Recherche autorite")
     */
    public function rechercherAutorite(Request $request){
        $search=$request->query->get('structure');
        $search=$request->query->get('user');
        $search=$request->query->get('profil');
        return new JsonResponse($this->autoriteManager->searchAutorite($search));
    }

    /**
    * @Delete("/api/autorite/{id}", name="delete_autorite")
    */
    public function deleteAutorite(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $autorite = $entityManager->getRepository(autorite::class)->find($id);
        $entityManager->remove($autorite);
        $entityManager->flush();

    return $this->redirectToRoute("autorites");
    }
    /**
     * @Put("/api/autorite/{id}")
     * @QMLogger(message="modifier autorite")
     */
    public function modifiAutorite(Request $request , $id){
       
        $autorite = $autoriteManager->getRepository(Autorite::class)->find($id);

        return $this->json(['status'=>200, "message"=>"autorite modifie avec succes"]);

    }

}
