<?php

namespace App\Controller;



use App\Entity\User;
use App\Annotation\QMLogger;
use App\Controller\BaseController;
use App\Entity\PointDeCoordination;
use App\Repository\ActiviteRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PointDeCoordinationRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PointDeCoordinationController extends BaseController
{
    private PointDeCoordinationRepository $pointDeCoordinationRepo;
    private ActiviteRepository $activiteRepo;

    public function __construct(PointDeCoordinationRepository $pointDeCoordinationRepo, ActiviteRepository $activiteRepo)
    {
        $this->pointDeCoordinationRepo = $pointDeCoordinationRepo;
        $this->activiteRepo = $activiteRepo;
    }
    /**
     * @Post("/api/pointDeCoordination", name="pointDeCoordinations")
     */
    public function addPointDeCoordination(Request $request ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $pointDeCoordination = $serializer->deserialize($request->getContent(), pointDeCoordination::class,'json');
        $errors = $validator->validate($pointDeCoordination);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }
    
    $activite= $this->activiteRepo->find($request->get('activite'));
    $pointDeCoordination->setActivite($activite);
    $pointDeCoordination->setCreateAt(new \Datetime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pointDeCoordination);
        $entityManager->flush();
    
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
       
    }

    /**
     * @Get("/api/pointdecoordination", name="pointDeCoordination")
     */
    public function listPointDeCoordination(): Response
    {
       
         $pointDeCoordinations = $this->pointDeCoordinationRepo->findAll();
         $response = $this->json($pointDeCoordinations, 200, [], ['groups' => 'pointDeCoordination:read']);

        return $response; 
    }
      /**
     * @Get("/api/PointDeCoordination/{id}")
     * @QMLogger(message="Details pointDeCoordination")
     */
    public function detailsPointDeCoordination($id){
        $pointDeCoordinations = $this->pointDeCoordinationRepo->find($id);
        return new JsonResponse($this->pointDeCoordinationManager->detailsPointDeCoordination($id));
    }

    /**
    * @Delete("/api/delete-pointDeCoordination/{id}", name="delete_pointDeCoordination")
    */
    public function deletePointDeCoordination(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $pointDeCoordination = $entityManager->getRepository(PointDeCoordination::class)->find($id);
        $entityManager->remove($pointDeCoordination);
        $entityManager->flush();

    return $this->redirectToRoute("pointDeCoordinations");
    }
     /**
     * @Put("/api/pointdecoordination/{id}", name="modifie_pointDeCoordination")
     * @QMLogger(message="modifier pointDeCoordination")
     */
    public function modifiPointDeCoordination($id, Request $request)
    {
        $pointDeCoordination = $this->pointDeCoordinationRepo->find($id);
        $activite= $this->activiteRepo->find($request->get('activite'));
        $pointDeCoordination->setActivite($activite);
        $pointDeCoordination->setLibelle($request->request->get('libelle'));
        $pointDeCoordination-> setCreateAt(new \Datetime());
        $pointDeCoordination->setStructureImpactee($request->request->get('structureImpactee'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pointDeCoordination);
            $entityManager->flush();
    
            return $this->json(['status'=>200, "message"=>"activite modifie avec succes"]);
    }
}
