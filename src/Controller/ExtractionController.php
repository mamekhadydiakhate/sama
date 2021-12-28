<?php

namespace App\Controller;

use App\Entity\Extraction;
use App\Controller\BaseController;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExtractionController extends BaseController
{
  
   public function extractionsEvaluation($dateDebut,$dateFin){
        // $apiHost = getenv('API_HOST');
        $evaluations=$this->em->getRepository(Evaluation::class)->extractionEvals($dateDebut, $dateFin);
         $filename = sprintf("ExtractionEvaluation-du-%s.csv", date('d-m-Y'));
        $response = new StreamedResponse(function () use ($evaluations,$filename) {
            $csv = fopen(self::URL_UPLOAD.$filename, 'w+');
            $entete=$entete=array("Prenom","Nom", "Matricule","Email","Tel","Structure","Direction","Structure","NoteGenerale","commentaire","nbObjectifs");
            fputcsv($csv, $entete, ';');
            foreach ($evaluations as $ev) {
                $tab['prenom']=$ev->getInterimaire()?$ev->getInterimaire()->getPersonne()?$ev->getInterimaire()->getPersonne()->getPrenom():'':"";
                $tab['nom']=$ev->getInterimaire()?$ev->getInterimaire()->getPersonne()?$ev->getInterimaire()->getPersonne()->getNom():'':"";
                $tab['matricule']=$ev->getInterimaire()?$ev->getInterimaire()->getMatricule():"";
                $tab['email']=$ev->getEmail()?$ev->getEmail()->getUser()?$ev->getEmail()->getUser()->getEmail():'':"";
                $tab['Structure']=$ev->getInterimaire()?$ev->getInterimaire()->getStructure()?$ev->getInterimaire()->getStructure()->getLibelle():'':"";
                $tab['direction']=$ev->getInterimaire()?$ev->getInterimaire()->getCurrentContrat()?$ev->getInterimaire()->getCurrentContrat()->getStructure()?$ev->getInterimaire()->getCurrentContrat()->getStructure()->getDirection()?$ev->getInterimaire()->getCurrentContrat()->getStructure()->getDirection()->getLibelle():'':"":"":"";
                $tab['structure']=$ev->getInterimaire()?$ev->getInterimaire()->getCurrentContrat()?$ev->getInterimaire()->getCurrentContrat()->getStructure()?$ev->getInterimaire()->getCurrentContrat()->getStructure()->getLibelle():"":"":"";
                $tab['commentaire']=$ev->getCommentaire()?$ev->getCommentaire():"";
                $tab['nbObjectifs']=$this->em->getRepository(Notation::class)->count(["evaluation"=>$ev->getId()]);
                fputcsv($csv, $tab, ';');
            }
            fclose($csv);
        });
        $response->send();
        $data= self::URL_EXTRACTION.$filename;
        return $this->render('extraction/index.html.twig', [
            'controller_name' => 'TypeStructureController',
        ]);    }
    
}
