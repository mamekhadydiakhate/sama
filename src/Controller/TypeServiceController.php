<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Entity\TypeService;
use App\Annotation\QMLogger;
use App\Repository\StructureRepository;
use App\Repository\TypeServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeServiceController extends AbstractController
{
    private TypeServiceRepository $typeServiceRepo;
    private StructureRepository $structureRepo;

    public function __construct(TypeServiceRepository $typeServiceRepo, StructureRepository $structureRepo)
    {
        $this->typeServiceRepo = $typeServiceRepo;
        $this->structureRepo = $structureRepo;
        $structure= new Structure ;

    }
    
    /**
     * @Post("/api/typeService", name="typeService")
     */
    public function addTypeService(Request $request ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $typeService = $serializer->deserialize($request->getContent(), typeService::class,'json');
        $errors = $validator->validate($typeService);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }
   
    $structure= $this->structureRepo->find($request->get('structure'));
    
        $typeService->setStructure($structure);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($typeService);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
    }
    /**
     * @Get("/api/typeService", name="typeServices")
     */
    public function listTypeService(): Response
    {
        $typeServices = $this->typeServiceRepo->findAll();
        $response = $this->json($typeServices, 200, [], ['groups' => 'typeService:read']);

        return $response;    
    }
      /**
     * @Get("/typeService/{id}")
     * @QMLogger(message="Details typeService")
     */
    public function detailsTypeService($id){
        $typeServices = $this->typeServiceRepo->find($id);
        
        return $this->json($typeServices);
        
    }
    
    /**
     * @Get("/api/recherchetypeService")
     * @QMLogger(message="Recherche typeService")
     */
    public function rechercherTypeService(Request $request){
        $search=$request->query->get('structure');
        $search=$request->query->get('user');
        $search=$request->query->get('profil');
        return new JsonResponse($this->typeServiceManager->searchtypeService($search));
    }

    /**
    * @Delete("/api/typeService/{id}", name="delete_typeService")
    */
    public function deleteTypeService(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $typeService = $entityManager->getRepository(TypeService::class)->find($id);
        $entityManager->remove($typeService);
        $entityManager->flush();

    return $this->redirectToRoute("typeServices");
    }
     /**
     * @Put("/api/typeService/{id}")
     * @QMLogger(message="modifier typeService")
     */
    public function modifiTypeService($id){
        $typeService = $this->typeServiceRepo->find($id);
        $typeService = $serializer->deserialize($request->getContent(), TypeService::class,'json');

        return new JsonResponse($this->typeServiceManager->modifitypeService($id));
    }
}
