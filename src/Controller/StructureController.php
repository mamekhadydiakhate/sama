<?php

namespace App\Controller;



use App\Entity\User;
use App\Entity\Structure;
use App\Annotation\QMLogger;
use App\Controller\BaseController;
use App\Repository\StructureRepository;
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

class StructureController extends BaseController
{
    private StructureRepository $structureRepo;

    public function __construct(StructureRepository $structureRepo)
    {
        $this->structureRepo = $structureRepo;
        $user= new User;
    }
    /**
     * @Post("/api/structure", name="structures")
     * 
     */
    public function addStructure(Request $request ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $structure = $serializer->deserialize($request->getContent(), structure::class,'json');
        $errors = $validator->validate($structure);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($structure);
        $entityManager->flush();
    
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
       
    }

    /**
     * @Get("/api/structure", name="structure")
     */
    public function listStructure(): Response
    {
       
         $structures = $this->structureRepo->findAll();
         
         return  $this->json($structures, 200, [], ['groups' => 'structure:read']);
    }
      /**
     * @Get("/api/structure/{id}")
     * @QMLogger(message="Details structure")
     */
    public function detailsStructure($id)
    {
        $structures = $this->structureRepo->find($id);
        return  $this->json($structures, 200, [], ['groups' => 'structure:read']);

    }

    /**
    * @Delete("/api/delete-structure/{id}", name="delete_structure")
    */
    public function deleteStructure(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $structure = $entityManager->getRepository(structure::class)->find($id);
        $entityManager->remove($structure);
        $entityManager->flush();

    return $this->redirectToRoute("structures");
    }
     /**
     * @Put("/api/structure/{id}")
     * @QMLogger(message="modifier structure")
     */
    public function modifiStructure($id, Request $request){
        $structure = $this->structureRepo->find($id);
        $pointDeCoordination->setLibelle($request->request->get('libelle'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($structure);
            $entityManager->flush();

            return $this->json(['status'=>200, "message"=>"structure modifie avec succes"]);

    }
}

