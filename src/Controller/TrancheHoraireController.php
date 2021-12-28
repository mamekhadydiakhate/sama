<?php

namespace App\Controller;



use App\Annotation\QMLogger;
use App\Controller\BaseController;
use App\Entity\TrancheHoraire;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrancheHoraireRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrancheHoraireController extends BaseController
{
    private TrancheHoraireRepository $trancheHoraireRepo;

    public function __construct(TrancheHoraireRepository $trancheHoraireRepo)
    {
        $this->trancheHoraireRepo = $trancheHoraireRepo;
       
    }
    /**
     * @Post("/trancheHoraire", name="trancheHoraires")
     */
    public function addTrancheHoraire(Request $request ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $trancheHoraire = $serializer->deserialize($request->getContent(), trancheHoraire::class,'json');
        $errors = $validator->validate($trancheHoraire);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }
    /*$message=(new\Swift_Message)
        ->setSubject('DCIRE, PILOTAGE PERFORMANCE')
        ->setFrom('xxxxx@orange-sonatel.com')
        ->setTo($user->getEmail())
        ->setBody("Votre activité est enregistré avec succé");
    $mailer->send($message);*/
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($trancheHoraire);
        $entityManager->flush();
    
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
       
    }

    /**
     * @Get("/trancheHoraire", name="trancheHoraire")
     */
    public function listtrancheHoraire(): Response
    {
       
         $trancheHoraires = $this->trancheHoraireRepo->findAll();
         
        return $this->json($trancheHoraires);
    }
      /**
     * @Get("trancheHoraire/{id}")
     * @QMLogger(message="Details trancheHoraire")
     */
    public function detailstrancheHoraire($id){
        $trancheHoraires = $this->trancheHoraireRepo->find($id);
        return new JsonResponse($this->trancheHoraireManager->detailstrancheHoraire($id));
    }

    /**
    * @Delete("/delete-trancheHoraire/{id}", name="delete_trancheHoraire")
    */
    public function deletetrancheHoraire(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trancheHoraire = $entityManager->getRepository(trancheHoraire::class)->find($id);
        $entityManager->remove($trancheHoraire);
        $entityManager->flush();

    return $this->redirectToRoute("trancheHoraires");
    }
     /**
     * @Put("/trancheHoraire/{id}", name="modifie_trancheHoraire")
     * @QMLogger(message="modifier trancheHoraire")
     */
    public function modifitrancheHoraire($id){
        $trancheHoraire = $this->trancheHoraireRepo->find($id);
        $trancheHoraire = $serializer->deserialize($request->getContent(), trancheHoraire::class,'json');

        return new JsonResponse($this->trancheHoraireManager->modifitrancheHoraire($id));
    }
}
