<?php

namespace App\Controller;




use App\Entity\User;
use App\Entity\Autorite;
use App\Entity\Evenement;
use App\Entity\Structure;
use App\Entity\Commentaire;
use App\Entity\Periodicite;
use App\Annotation\QMLogger;
use App\Service\BaseService;
use FOS\UserBundle\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use App\Controller\BaseController;
use App\Repository\UserRepository;
use App\Entity\HistoriqueEvenement;
use App\Repository\EvenementRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PeriodiciteRepository;
use App\Repository\TrancheHoraireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HistoriqueEvenementRepository;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EvenementController extends BaseController
{
    private UserRepository $userRepo;
    private EvenementRepository $evenementRepo;
    private StructureRepository $structureRepo;
    private PeriodiciteRepository $periodiciterepo;
    private TrancheHoraireRepository $trancheHoraireRepo;
    private HistoriqueEvenementRepository $historiqueEvenementRepo;
    private BaseService $baseService; 
    private $archive;

    public function __construct(EntityManagerInterface $manager, BaseService $baseService,UserRepository $userRepo ,EvenementRepository $evenementRepo,
                periodiciteRepository $periodiciteRepo,StructureRepository $structureRepo ,TrancheHoraireRepository $trancheHoraireRepo ,HistoriqueEvenementRepository $historiqueEvenementRepo)
    {
        $this->userRepo = $userRepo;
        $this->evenementRepo = $evenementRepo;
        $this->periodiciteRepo = $periodiciteRepo;
        $this->structureRepo = $structureRepo;
        $this->historiqueEvenementRepo = $historiqueEvenementRepo;
        $this -> archive = $manager;
        $this->baseService=$baseService;
        

    }
    
    /**
     * @Post("/api/evenement", name="evenements")
     */
    public function addEvenement(EntityManagerInterface $manager, Request $request, MailerInterface $mailer ,ValidatorInterface $validator ,SerializerInterface $serializer): Response
    {

        $evenement = $serializer->deserialize($request->getContent(), evenement::class,'json');
        $errors = $validator->validate($evenement);
    if (count($errors) > 0)
    {
        $errorsString =$serializer->serialize($errors,"json");
        
        return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
    }
    

    $user = $this->getUser();
    $structure = $user->getStructure();
    $evenement->setThematique($request->request->get('thematique'));
        $evenement->setUser($user);
        $evenement->setStructure($structure);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($evenement);
        $data = array(
            'to' => $user->getEmail(),
            'cc'=>array('mamekhady.diakhate@orange-sonatel.com','genvievesebiasylvie.mendy@orange-sonatel.com'),
            'subject' => 'Données de connexion à la plateforme Suivi des Activités et de la Roadmap',
            'body' => 'Bonjour '.$user->getPrenom().' '.$user->getNom().',
            <br><br>Merci d\'avoir renseigner la Roadmap '. '<br>'
        );
        $this->baseService->sendMail($data);
        $entityManager->flush();
        return new JsonResponse("succes",Response::HTTP_CREATED,[],true);
      
    }

    /**
     * @Get("/api/evenement", name="evenement")
     */
    public function listEvenement(): Response
    {
        
        $evenements = $this->evenementRepo->findAll();
        return $this->json($evenements, 200, [], ['groups' => 'evenement:read']);
    }

    
      /**
     * @Get("/api/evenement/{id}")
     * @QMLogger(message="Details evenement")
     */
    public function detailsEvenement($id){
        $evenements = $this->evenementRepo->find($id);
        $response = $this->json($evenements, 200, [], ['groups' => 'evenement:detail']);

        return $response; 
        
    }
    
    /**
     * @Get("/api/rechercheevenement")
     * @QMLogger(message="Recherche evenement")
     */
    public function recherchErevenement(Request $request){
        $search=$request->query->get('structure');
        $search=$request->query->get('user');
        $search=$request->query->get('profil');
        return new JsonResponse($this->evenementManager->searchEvenement($search));
    }

    /**
    * @Delete("/api/evenement/{id}", name="delete_evenement")
    */
    public function deleteEvenement(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $evenement = $entityManager->getRepository(Evenement::class)->find($id);
        $entityManager->remove($evenement);
        $entityManager->flush();

    return $this->redirectToRoute("evenements");
    }
     /**
     * @Put("/api/evenement/{id}")
     * @QMLogger(message="modifier evenement")
     */
    public function modifiEvenement($id, Evenement $evenement, Request $request)
    {
        $evenement = $this->evenementRepo->find($id);
        $user = $this->getUser();
        $structure = $user->getStructure();
        $dateDebut = ($request->request->get('dateDebut'));
        $dateFin= ($request->request->get('dateFin'));
        $evenement->setDateDebut(new \DateTime($dateDebut)); 
        $evenement->setDateFin(new \DateTime($dateFin));    
        $evenement->setThematique($request->request->get('thematique'));

        $evenement->setUser($user);
        $evenement->setStructure($structure);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($evenement);
        
        $entityManager->flush();

        return $this->json(['status'=>200, "message"=>"Evenement modifie avec succes"]);
    }

    /**
     * @Get("/api/evenement/archive/{id}")
     * @var EntityManagerInterface
     */
    public function __invoke(Evenement $data): Evenement
    {
         $data -> setArchives(1);
        //$this -> archive -> persist($update);
        $this -> archive -> flush();
        return $data;
    }
}