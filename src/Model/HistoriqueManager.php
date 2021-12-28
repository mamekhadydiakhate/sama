<?php

namespace App\Model;

use App\Entity\User;
use App\Entity\Activite;
use App\Entity\Evenement;
use App\Entity\Historique;
use App\Service\BaseService;
use App\Mapping\HistoriqueMapping;
use App\Service\ConnectedUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HistoriqueManager extends BaseManager{
    private $tokenStorage;
    private $historiqueMapping;
    public function __construct(HistoriqueMapping $historiqueMapping,TokenStorageInterface $tokenStorage,BaseService $baseService, \Swift_Mailer $mailer, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->tokenStorage=$tokenStorage;
        $this->historiqueMapping=$historiqueMapping;
        parent::__construct($baseService, $mailer, $serializer, $validator, $em);
    }
    public function addHistorique($data){
        $historique=new historique();
        $historique->setUser(ConnectedUserService::getConnectedUser($this->tokenStorage,$this->em->getRepository(User::class)));
        $historique->setAddresseIp($_SERVER['REMOTE_ADDR']);
        $historique->setActivite(isset($data['activite'])?$this->em->getRepository(Activite::class)->findOneBy(["libelle"=>$data['activite']]):null);
        $historique->setEvenement(isset($data['evenement'])?$this->em->getRepository(Evenement::class)->findOneBy(["libelle"=>$data['evenement']]):null);
        $this->em->persist($historique);
        $this->em->flush();
    }

    public function getUserHistoriques($id,$page){
        $historiques=$this->em->getRepository(Historique::class)->findBy(["user"=>$id],["id"=>"DESC"],$this->LIMIT,($page - 1) * $this->LIMIT);
        if (!$historiques){
            return array("status"=>false,"code"=>500,"message"=>"Aucune historique pour cet utilisateur");
        }
        return array("status"=>true,"code"=>200,"data"=>$this->historiqueMapping->hydratehistoriques($historiques));

    }

    public function getAllHistoriques($page){
        $historiques=$this->em->getRepository(Historique::class)->findBy([],["id"=>"DESC"],$this->LIMIT,($page - 1) * $this->LIMIT);
        if (!$historiques){
            return array("status"=>false,"code"=>500,"message"=>"Aucune historique disponible");
        }
        return array("status"=>true,"code"=>200,"data"=>$this->historiqueMapping->hydratehistoriques($historiques));

    }

    public function historiqueBetween($page,$id,$data){
        $start=new \DateTime($data['debut']);
        $end=new \DateTime($data['fin']);
        $historiques=$this->em->getRepository(Historique::class)->historiqueBetween($id,$start,$end,$page,$this->LIMIT);
        if (!$historiques){
            return array("status"=>false,"code"=>500,"message"=>"Aucune historique disponible");
        }
        $total=$this->em->getRepository(historique::class)->counthistoriqueBetween($id,$start,$end);
        return array("status"=>true,"code"=>200,"total"=>$total,"data"=>$this->historiqueMapping->hydratehistoriques($historiques));
    }
}
