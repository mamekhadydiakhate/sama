<?php

namespace App\Controller;




use App\Entity\Periodicite;
use App\Annotation\QMLogger;
use FOS\UserBundle\Mailer\Mailer;
use App\Controller\BaseController;
use App\Repository\PeriodiciteRepository;
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

class PeriodiciteController extends BaseController
{
    private PeriodiciteRepository $periodiciteRepo;

    public function __construct(PeriodiciteRepository $periodiciteRepo)
    {
        $this->periodiciteRepo = $periodiciteRepo;
        $dateDebut = new DateTime();
        $dateFin= new DateTime()<=$dateDebut;
       
       
    }
    
    /**
     * @Post("/api/periodicite", name="periodicites")
     */
    public function addPeriodicite(Request $request ,ValidatorInterface $validator,SerializerInterface $serializer): Response
    {

        $periodicite = $serializer->deserialize($request->getContent(), periodicite::class,'json');
        $errors = $validator->validate($periodicite);
    if (count($errors) > 1)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }

   
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($periodicite);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
      
    }

    /**
     * @Get("/api/periodicite", name="periodicite")
     */
    public function listPeriodicite(): Response
    {
       
         $periodicites = $this->periodiciteRepo->findAll();
         
         return $this->json($periodicites, 200, [], ['groups' => 'periodicite:read']);
    }
      /**
     * @Get("/api/periodicite/{id}")
     * @QMLogger(message="Details periodicite")
     */
    public function detailsPeriodicite($id){
        $periodicites = $this->periodiciteRepo->find($id);
        
        return $this->json($periodicites);
        
    }
    
    /**
     * @Get("/api/rechercheperiodicite")
     * @QMLogger(message="Recherche periodicite")
     */
    public function rechercherPeriodicite(Request $request){
        $search=$request->query->get('structure');
        $search=$request->query->get('user');
        $search=$request->query->get('profil');
        return new JsonResponse($this->periodiciteManager->searchPeriodicite($search));
    }

    /**
    * @Delete("/api/periodicite/{id}", name="delete_periodicite")
    */
    public function deletePeriodicite(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $periodicite = $entityManager->getRepository(Periodicite::class)->find($id);
        $entityManager->remove($periodicite);
        $entityManager->flush();

    return $this->redirectToRoute("periodicites");
    }
     /**
     * @Put("/api/periodicite/{id}")
     * @QMLogger(message="modifier periodicite")
     */
    public function modifiPeriodicite($id){
        $periodicite = $this->periodiciteRepo->find($id) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($periodicite);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);

    }
}
