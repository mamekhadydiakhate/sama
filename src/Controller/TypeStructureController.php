<?php

namespace App\Controller;

use App\Entity\TypeStructure;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeStructureController extends BaseController
{
    /**
     * @Route("/type/structure", name="type_structure")
     */
    public function index(): Response
    {
        return $this->render('type_structure/index.html.twig', [
            'controller_name' => 'TypeStructureController',
        ]);
    }
}
