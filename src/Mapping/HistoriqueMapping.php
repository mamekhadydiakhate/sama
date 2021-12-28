<?php

namespace App\Mapping;

use App\Entity\Historique;
use App\Model\BaseManager;

class HistoriqueMapping extends BaseMapping{

    public function hydrateHistoriques($historiques){
        $tabhistoriques=array();
        foreach ($historiques as $historique){
            $tabhistoriques[]= array(
            $this->ID_KEY=>$historique->getId(),
            "activite"=>array(
                $this->ID_KEY=>$historique->getActivite()?$historique->getActivite()->getId():null,
                $this->LIBELLE_KEY=>$historique->getActivite()?$historique->getActivite()->getLibelle():null,
            ),
            "evenement"=>array(
                $this->ID_KEY=>$historique->getEvenement()?$historique->getEvenement()->getId():null,
                $this->LIBELLE_KEY=>$historique->getEvenement()?$historique->getEvenement()->getLibelle():null,
            ),
            "user"=>array(
                $this->ID_KEY=>$historique->getUser()?$historique->getUser()->getId():null,
                $this->PRENOM_KEY=>$historique->getUser()?$historique->getUser()->getPrenom():null,
                $this->PRENOM_KEY=>$historique->getUser()?$historique->getUser()->getNom():null,
            ),
            "adresseIp"=>$historique->getAddresseIp(),
                "date"=>$historique->getDate()?date_format($historique->getDate(),'Y-m-d'):null,
        );
        }
        return $tabhistoriques;
    }
}
