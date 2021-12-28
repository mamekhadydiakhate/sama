<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Workflow;
use App\Service\BaseService;
use FOS\UserBundle\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use App\Controller\BaseController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkflowController extends BaseController
{
    private UserRepository $userRepo;
    private BaseService $baseService;

    public function __construct(UserRepository $userRepo,BaseService $baseService, MailerInterface $mailer){
        $this->userRepo=$userRepo;
        $this->mailer=$mailer;
        $this->baseService=$baseService;

    }
  /**
     * @Post("admin/workflow")
     */
    public function sendworkflow(MailerInterface $mailer, Request $request): Response
    {
       $user=$this->getUser();
        $data = array(
            'to' => 'no-reply@orange-sonatel.com',
            'cc'=>array('mamekhady.diakhate@orange-sonatel.com','genvievesebiasylvie.mendy@orange-sonatel.com'),
            'subject' => 'Données de connexion à la plateforme Suivi des Activités et de la Roadmap',
            'body' => 'Bonjour '.$user->getPrenom().' '.$user->getNom().',
            <br><br>Merci de renseigner le suivi d\'actitivé de la semaine'. '<br>'
        );
        $this->baseService->sendMail($data);
        
        
        return $this->json(['status'=>200, "message"=>"workflow envoyé avec succés"]);

       
    }
}
