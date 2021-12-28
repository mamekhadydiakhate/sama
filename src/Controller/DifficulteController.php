<?php

namespace App\Controller;



use App\Entity\User;
use App\Entity\Activite;
use App\Entity\Difficulte;
use App\Annotation\QMLogger;
use App\Form\DifficulteType;
use App\Controller\BaseController;
use App\Repository\DifficulteRepository;
use App\Repository\ActiviteRepository;
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

class DifficulteController extends BaseController
{
    private DifficulteRepository $difficulteRepo;
    private activiteRepository $activiteRepo;

    public function __construct(DifficulteRepository $difficulteRepo, ActiviteRepository $activiteRepo)
    {
        $this->difficulteRepo = $difficulteRepo;
        $this->activiteRepo = $activiteRepo;
        $user= new User;
    }
    /**
     * @Post("/api/difficulte", name="difficultes")
     */
    public function addDifficulte(Request $request ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $difficulte = $serializer->deserialize($request->getContent(), Difficulte::class,'json');
        $errors = $validator->validate($difficulte);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }
    /*$message=(new\Swift_Message)
        ->setSubject('DCIRE, PILOTAGE PERFORMANCE')
        ->setFrom('xxxxx@orange-sonatel.com')
        ->setTo($user->getEmail())
        ->setBody("Votre difficulté est enregistré avec succé");
    $mailer->send($message);*/  
    #$form = $this->createForm(DifficulteType::class);
        $activite= $this->activiteRepo->find($request->get('activite'));
        //dd($activite);
        $difficulte->setActivite($activite);
        $difficulte->setCreatedAt(new \Datetime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($activite);
        $entityManager->persist($difficulte);
        $entityManager->flush();

        return $this->json($difficulte, 200, [], ['groups' => 'difficulte:read']);       
    }

    /**
     * @Get("/api/difficulte", name="difficulte")
     */
    public function listDifficulte(): Response
    {
       
         $difficultes = $this->difficulteRepo->findAll();

        return $this->json($difficultes, 200, [], ['groups' => 'difficulte:read']);

    }
      /**
     * @Get("/api/difficulte/{id}")
     * @QMLogger(message="Details difficulte")
     */
    public function detailsDifficulte($id){
        $difficultes = $this->difficulteRepo->find($id);
        return new JsonResponse($this->difficulteManager->detailsDifficulte($id));
    }

    /**
    * @Delete("/api/difficulte/{id}", name="delete_difficulte")
    */
    public function deleteDifficulte(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $difficulte = $entityManager->getRepository(difficulte::class)->find($id);
        $entityManager->remove($difficulte);
        $entityManager->flush();

        return $this->json(['status'=>200, "message"=>"difficulte efface avec succes"]);    }
        
     /**
     * @Put("/api/difficulte/{id}")
     * @QMLogger(message="modifier difficulte")
     */
    public function modifiDifficulte($id, Request $request){
        $difficulte = $this->difficulteRepo->find($id);
        $activite= $this->activiteRepo->find($request->get('activite'));
        $difficulte->setActivite($activite);
        $difficulte->setCreatedAt(new \Datetime());
        $difficulte->setDescription($request->request->get('description'));
        $difficulte->setCause($request->request->get('cause'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($difficulte);
            $entityManager->flush();

        return $this->json(['status'=>200, "message"=>"difficulte modifie avec succes"]);
    }
}
