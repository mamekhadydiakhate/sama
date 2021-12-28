<?php
       namespace App\Mapping;
       use App\Entity\User;
       use Symfony\Component\Filesystem\Filesystem;
       use Symfony\Component\HttpFoundation\JsonResponse;
       
       class UserMapping extends BaseMapping {
           public function createUser($data){
                $user=new User();
                $user=$this->setUserData($data,$user);
                $user->setUsername(strtolower($data['nom'])."".strtolower($data["matricule"]));
             //  $user->setProfil(isset($data['profil'])?$data['profil']:null);
               $user->setEnabled(true);
               //$password=$this->genererPassword(8);
               $password= 'passer';
               $user->setPassword($this->encoder->encodePassword($user,$password));
               $retour["user"]=$user;
               $retour["password"]=$password;
               return $retour;
            }
           public function getAllUsers($users,$all,$pages){
               $listeUsers=array();
               if(count($users) ==null){
                   return new JsonResponse(array($this->CODE_KEY=>500,$this->SUCCESS_KEY=>$this->STATUS_FALSE,$this->MESSAGE_KEY=>"Aucun utilisateur"));
               }
               foreach ($users as $user){
                   $listeUsers[]=$this->hydrateListUser($user);
               }
               if ($pages['limit'] === null || $pages['offset'] === null){
                   return new JsonResponse(array($this->CODE_KEY=>200,$this->SUCCESS_KEY=>$this->STATUS_TRUE,"total"=>count($listeUsers),$this->DATA_KEY=>$listeUsers));
               }
                return new JsonResponse(array($this->CODE_KEY=>$this->CODE_200,$this->SUCCESS_KEY=>$this->STATUS_TRUE,"total"=>$all,"totalCurrentPage"=>count($users),"totalNextPage"=>$pages['totalNext'],"pages"=>array("precedent"=>$pages['previous'],"enCours"=>$pages['current'],"suivant"=>$pages['next'],"dernierPage"=>$pages['totalPages']),$this->DATA_KEY=>$listeUsers));
           }
       
           public function detailsUser($user){
               if( $user==null){
                   return array($this->CODE_KEY=>500,$this->SUCCESS_KEY=>$this->STATUS_FALSE,$this->MESSAGE_KEY=>"Utilisateur inexistant");
               }
               return array($this->CODE_KEY=>$this->CODE_200,$this->SUCCESS_KEY=>$this->STATUS_TRUE,$this->DATA_KEY=>$this->hydrateUser($user));
           }
       
           public function hydrateUser($user){
             return array(
                   $this->ID_KEY=>$user->getId(),
                   $this->NOM_KEY=>$user->getNom(),
                   $this->PRENOM_KEY=>$user->getPrenom(),
                   $this->EMAIL_KEY=>$user->getEmail(),
                   $this->USERNAME_KEY=>$user->getUsername(),
                   //$this->TELEPHONE_KEY=>$user->getTelephone(),
                   $this->MATRICULE_KEY=>$user->getMatricule(),
                   "service"=>$user->getService(),
                 "etat"=>$user->isEnabled()==true?"Actif":"Inactif"
                 /*  "fonction"=>$user->getFonction(),
                   "isManager"=>$user->getIsManager(),
                   $this->PROFIL_KEY=>array(
                       "id"=> $user->getProfil()?$user->getProfil()->getId():null,
                       $this->LIBELLE_KEY=> $user->getProfil()?$user->getProfil()->getLibelle():null,
                       "description"=> $user->getProfil()?$user->getProfil()->getDescription():null,
                       ),
                   $this->AVATAR_KEY=>$user->getAvatar()?$_ENV["API_HOST"]."/public/uploads/images/".$user->getAvatar():null,
               */);
           }
       
       
       
           public function hydrateListUser($user){
             return array(
                   $this->ID_KEY=>$user->getId(),
                   $this->NOM_KEY=>$user->getNom(),
                   $this->PRENOM_KEY=>$user->getPrenom(),
                   $this->EMAIL_KEY=>$user->getEmail(),
                   $this->MATRICULE_KEY=>$user->getMatricule(),
                  "etat"=>$user->isEnabled()==true?"Actif":"Inactif"
                 /*  $this->PROFIL_KEY=>array(
                       "id"=> $user->getProfil()?$user->getProfil()->getId():null,
                       $this->LIBELLE_KEY=> $user->getProfil()?$user->getProfil()->getLibelle():null,
                       "description"=> $user->getProfil()?$user->getProfil()->getDescription():null,
                       ),*/
                  );
           }
       
           public function hydrateManager($user){
              return array(
                   $this->ID_KEY=>$user->getId(),
                   $this->NOM_KEY=>$user->getNom(),
                   $this->PRENOM_KEY=>$user->getPrenom(),
                   $this->EMAIL_KEY=>$user->getEmail(),
                   $this->USERNAME_KEY=>$user->getUsername(),
                   $this->TELEPHONE_KEY=>$user->getTelephone(),
                   'matricule'=>$user->getMatricule(),
                   'service'=>$user->getService(),
       
                  /*     "photo"=>$user->getAvatar()?$_ENV["API_HOST"]."/public/uploads/images/".$user->getAvatar():null,
                       'poste'=>$user->getFonction(),
                       'structure'=>array(
                           $this->ID_KEY=>$user->getStructure()?$user->getStructure()->getId():null,
                           $this->LIBELLE_KEY=>$user->getStructure()?$user->getStructure()->getLibelle():null,
                           'pole'=>$user->getStructure()?$user->getStructure()->getPole():null,
                           'departement'=>$user->getStructure()?$user->getStructure()->getDepartement():null,
                           'typeStructure'=>array(
                              "id"=> $user->getStructure()?$user->getStructure()->getTypeStructure()->getId():null,
                              "libelle"=>$user->getStructure()?$user->getStructure()->getTypeStructure()->getLibelle():null,
                           )
                       ),
                      "direction"=>array(
                          "id"=>$user->getStructure()?$user->getStructure()->getDirection()?$user->getStructure()->getDirection()->getId():null:null,
                          "libelle"=>$user->getStructure()?$user->getStructure()->getDirection()?$user->getStructure()->getDirection()->getLibelle():null:null,
                      )*/
               );
           }
       
           public function addImage($image,$directory){
               $fichier = md5(uniqid()).'.'.$image->guessExtension();
               $image->move(
                   $directory,
                   $fichier
               );
               return $fichier;
           }
       
           private function setUserData($data,$user){
               $nom = isset($data['nom']) ? $data['nom'] : $user->getNom();
               $prenom = isset($data['prenom']) ? $data['prenom'] : $user->getPrenom();
               $email = isset($data['email']) ? $data['email'] : $user->getEmail();
               $matricule = isset($data['matricule']) ? $data['matricule'] : $user->getMatricule();
               $user->setEmail($email);
               $user->setService(isset($data['service'])?$data['service']:null);
               $user->setProfil( isset($data['profile'])? $data['profile']:$user->getProfil());
               $user->setNom($nom);
               $user->setPrenom($prenom);
               $user->setMatricule($matricule);
               return $user;
           }
       
           public function updateUser($data,$user){
               //$profil = isset($data['profil']) ? $data['profil'] : $user->getProfil();
               $username = isset($data['username']) ? $data['username'] : $user->getUsername();
               $user->setUserName($username);
           //    $user->setProfil($profil);
               return $this->setUserData($data,$user);
           }
       
           public function enabledUser($action, $user)
           {
               $action == 'activer' ? $user->setEnabled(true) : $user->setEnabled(false);
               $message = $action == 'activer' ? 'Utilisateur activé!' : 'Utilisateur désactivé!';
               return array($this->SUCCESS_KEY=>true,$this->CODE_KEY=>200,$this->DATA_KEY=> array($this->MESSAGE_KEY => $message));
           }
           public function genererPassword($nbChar){
               return substr(str_shuffle(
                   'abcdefghijklmnopqrstuvwxyzABCEFGHIJKLMNOPQRSTUVWXYZ0123456789'),1, $nbChar);
           }
           public function genererChiffre($nbChar){
               return substr(str_shuffle(
                   '0123456789'),1, $nbChar);
           }
           
    }
       
       