<?php


namespace App\Service;


use App\Entity\User;
use App\Entity\Action;
use App\Entity\Contrat;
use App\Entity\Interimaire;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BaseService
{
    private $mailer;
    protected $em;
    protected $interimaireMapping;
    protected $tokenStorage;
    protected $cc=array('mohamed.sall@orange-sonatel.com','binetou.diallo@orange-sonatel.com',"babacar.fall4@orange-sonatel.com");
    public function __construct(\Swift_Mailer $mailer, EntityManagerInterface $em,TokenStorageInterface $tokenStorage)
    {
        $this->mailer=$mailer;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    public function sendMail($data){
         $message = (new \Swift_Message())
            ->setFrom('no-reply@orange-sonatel.com')
            ->setTo($data['to']);
         if (isset($data['cc'])){
             $message->setCc($data['cc']);
         }
           
    }

    public function uploadFile($file,$directory){
        $allowed  = ['jpg', 'jpeg', 'png', 'gif','pdf','PDF','JPG','JPEG','PNG'];
        if ($file){
            $fichier = md5(uniqid()).'.'.$file->guessExtension();
            $bool=false;
            if (in_array($file->guessExtension(),$allowed)){
                $bool=true;
            }
            if ($bool){
                $file->move(
                    $directory,
                    $fichier
                );
            }
            return array(
                "filename"=>$fichier,
                "isValid"=>$bool
            );
        }else{
            return array(
                "filename"=>null,
                "isValid"=>false
            );
        }

    }
     public function setSqlAdvancedSearch($data){
        $i=0;
        $req='';
        $join=array();
        if (count($data)==0){
            return array("success"=>false,"code"=>502,"data"=>array("message"=>"Veuillez renseigner un filtre pour la recherche!"));
        }
        foreach ($data as $key=>$value){
            if ($this->generateKey($key)=='p'){
                if (count($data)>=1){
                    if($i==0){
                        $req.=$this->generateKey($key).".".$key." LIKE '%".$value."%'";
                    }elseif ($i>0 && $i<=count($data)-1){
                        $req.=" AND ".$this->generateKey($key).".".$key." LIKE '%".$value."%'";
                    }
                }
            }elseif ($this->generateKey($key)=='i' && $key=='agence'){
                $join['i']=" JOIN agence a ON i.agence_id = a.id ";
                if (count($data)>=1){
                    if($i==0){
                        $req.=$this->generateKey($key).".".$key."_id = ".$value;
                    }elseif ($i>0 && $i<=count($data)-1){
                        $req.=" AND ".$this->generateKey($key).".".$key."_id =  ".$value;
                    }
                }
            }elseif ($this->generateKey($key)=='i' && $key!='agence'){
                if (count($data)>=1){
                    if($i==0){
                        $req.=$this->generateKey($key).".".$key." LIKE '%".$value."%'";
                    }elseif ($i>0 && $i<=count($data)-1){
                        $req.=" AND ".$this->generateKey($key).".".$key." LIKE '%".$value."%'";
                    }
                }
            }elseif ($this->generateKey($key)=='c'){
                $join['c']=" JOIN contrat c ON c.interimaire_id = i.id ";

                if ($key=="date_signature"){
                    $p= " YEAR(c.date_signature) = $value";
                }else{
                    $p=$this->generateKey($key).".".$key." LIKE '%".$value."%'";
                }
                if (count($data)>=1){
                    if($i==0){
                        $req.=$p;
                    }elseif ($i>0 && $i<=count($data)-1){
                        $req.=" AND ".$p;
                    }
                }
            }elseif ($this->generateKey($key)=='s' &&  $key!='direction'){
             //   $join['s']=" JOIN structure s ON i.structure_id = s.id  ";

                if (count($data)>=1){
                    if($i==0){
                        $req.=$this->generateKey($key).".".$key." LIKE '%".$value."%'";
                    }elseif ($i>0 && $i<=count($data)-1){
                        $req.=" AND ".$this->generateKey($key).".".$key." LIKE '%".$value."%'";
                    }
                }
            }elseif ($this->generateKey($key)=='s' &&  $key=='direction'){

              //  $join['s']=" JOIN structure s ON i.structure_id = s.id  ";
                if (count($data)>=1){
                    if($i==0){
                        $req.=$this->generateKey($key).".".$key."_id= ".$value;
                    }elseif ($i>0 && $i<=count($data)-1){
                        $req.=" AND ".$this->generateKey($key).".".$key."_id= ".$value;
                    }
                }
            }
            $i++;
        }
         return array("req"=>$req,"join"=>$join);
    }
    private function generateKey($key){
        $cle='';
        if ($key=='nom' || $key=='prenom' ||$key=='matricule' ||  $key=='email'||  $key=='fonction'){
            $cle='p';
        }elseif ($key=='agence'){
            $cle='i';
        }elseif ( $key=='date_signature' ){
            $cle='c';
        }elseif ($key=="direction" || $key=='departement' || $key=='service'){
            $cle='s';
        }
        return $cle;
    }

}