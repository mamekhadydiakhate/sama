<?php


namespace App\Model;


use App\Entity\User;
use App\Mapping\ProfilMapping;
use App\Repository\ProfilRepository;
use App\Service\BaseService;
use App\Service\ConnectedUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfilManager extends BaseManager
{
    private $profilMapping;
    private $profilRepository;
    public function __construct(ProfilRepository $profilRepository,ProfilMapping $profilMapping,BaseService $baseService, \Swift_Mailer $mailer, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->profilRepository = $profilRepository;
        $this->profilMapping = $profilMapping;
        parent::__construct($baseService, $mailer, $serializer, $validator, $em);
    }

    public function addprofil($data)
    {
        $profil =$this->profilMapping->addProfil($data);
        $errors = $this->validator->validate($profil);
        if ($errors->count()>0){
            $err = json_decode( $this->serializer->serialize($errors, 'json'),true);
            return array("code"=>500,"status"=>false,"message"=>$err['detail']);
        }
        $this->em->persist($profil);
        $this->em->flush();
        return array("code"=>201,"status"=>true,"message"=>"Profil créé avec succés");
    }
    public function updateProfil($data,$id){
        $profil=$this->profilRepository->find($id);
        if (!$profil) {
            return array($this->SUCCESS_KEY => false, $this->CODE_KEY => 500,$this->MESSAGE_KEY => 'Profil inexistant!');
        }
        $profil=$this->profilMapping->updateProfil($data,$profil);
        $errors = $this->validator->validate($profil);
        if ($errors->count()>0){
            $err = json_decode( $this->serializer->serialize($errors, 'json'),true);
            return  array("code"=>500,"status"=>false,"message"=>$err['detail']);
        }
        $this->em->persist($profil);
        $this->em->flush();
        return array("code"=>200,"status"=>true,$this->MESSAGE_KEY=>"Profil modifié avec succes!");
    }

    public function listprofil()
    {
        //$connectedUser=ConnectedUserService::getConnectedUser($this->tokenStorage,$this->em->getRepository(User::class));
         $profils = $this->profilRepository->findAll();
        if (count($profils)==0){
            return array($this->SUCCESS_KEY => false, $this->CODE_KEY => 500, $this->MESSAGE_KEY => 'Profils inexistants!');
        }
        return  array("code"=>200,"status"=>true,"data"=>$this->profilMapping->allProfil($profils));
    }


    public function deleteProfil($id)
    {
        $profil=$this->profilRepository->find($id);
        if (!$profil) {
            return array($this->SUCCESS_KEY => false, $this->CODE_KEY => 500, $this->DATA_KEY => array($this->MESSAGE_KEY => 'Profil inexistant!'));
        }
        $users=$this->em->getRepository(User::class)->findBy(["profil"=>$profil->getId()]);
        foreach ($users as $user){
            $user->setProfil(null);
            $this->em->persist($profil);
        }
        $this->em->remove($profil);
        $this->em->flush();
        return array("code"=>200,"status"=>true,"message"=>"Profil supprimé avec succes");
    }
}