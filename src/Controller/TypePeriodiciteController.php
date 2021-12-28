<?php

namespace App\Controller;

use App\Entity\TypePeriodicite;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypePeriodiciteController extends BaseController
{
    /**
     * @Route("/type/periodicite", name="type_periodicite")
     */
    public function index(): Response
    {
        return $this->render('type_periodicite/index.html.twig', [
            'controller_name' => 'TypePeriodiciteController',
        ]);
    }
}
