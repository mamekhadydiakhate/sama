<?php

namespace App\Controller;



use App\Entity\User;
use App\Entity\Interim;
use App\Annotation\QMLogger;
use FOS\UserBundle\Mailer\Mailer;
use App\Controller\BaseController;
use App\Repository\InterimRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Role\Role;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterimController extends BaseController
{
    private InterimRepository $interimRepo;

    public function __construct(InterimRepository $interimRepo)
    {
        $this->interimRepo = $interimRepo;
       
    }
    
    /**
     * @Post("/api/admin/addInterim", name="interims" )
     * 
     */
    public function addInterim(Request $request ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $interim = $serializer->deserialize($request->getContent(), Interim::class,'json');
        $errors = $validator->validate($interim);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }
   
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($interim);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
      
    }

    /**
     * @Get("/admin/interim", name="interim")
     */
    public function listInterim(): Response
    {
       
         $interims = $this->interimRepo->findAll();
         
        return $this->json($interims);
    }
      /**
     * @Get("/interim/{id}" )
     * @QMLogger(message="Details interim")
     */
    public function detailsInterim($id){
        $interims = $this->interimRepo->find($id);
        return $this->json($interims);
        
    }

    /**
    * @Delete("/admin/interim/{id}", name="delete_interim" )
    */
    public function deleteInterim(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $interim = $entityManager->getRepository(interim::class)->find($id);
        $entityManager->remove($interim);
        $entityManager->flush();

    return $this->redirectToRoute("interims");
    }
     /**
     * @Put("/admin/interim/{id}" )
     * @QMLogger(message="modifier interim")
     */
    public function modifiInterim($id, Request $request){
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($interim);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
    }
}
