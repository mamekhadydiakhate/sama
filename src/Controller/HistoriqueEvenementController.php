<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\HistoriqueEvenement;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueEvenementController extends BaseController
{
    /**
     * @Route("/historique/evenement", name="historique_evenement")
     */
    public function index(): Response
    {
        return $this->render('historique_evenement/index.html.twig', [
            'controller_name' => 'HistoriqueEvenementController',
        ]);
    }
}