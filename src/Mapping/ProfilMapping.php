<?php


namespace App\Mapping;


use App\Entity\Profil;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfilMapping extends BaseMapping
{
    public function addProfil($data){
        $profil=new Profil();
        $profil=$this->setProfilData($data,$profil);
        return $profil;
    }
    private function setProfilData($data,$profil){
        $libelle = isset($data['libelle']) ? $data['libelle'] : $profil->getLibelle();
        $profil->setLibelle($libelle);
        return $profil;
    }
    public function updateProfil($data,$profil){
        return $this->setProfilData($data,$profil);
    }
    public function hydrateProfil($profil){
        return array(
            $this->ID_KEY=>$profil?$profil->getId():null,
            $this->LIBELLE_KEY=>$profil?$profil->getLibelle():null,
        );
    }
    public function allProfil($datas){
        $tabProfil=array();
        if(count($datas) ==null){
            return new JsonResponse(array($this->CODE_KEY=>$this->CODE_200,$this->SUCCESS_KEY=>$this->STATUS_FALSE,$this->MESSAGE_KEY=>"Aucun profil"));
        }
        foreach ($datas as $data){
            $tabProfil[]=$this->hydrateProfil($data);
        }
        return $tabProfil;
    }
}
