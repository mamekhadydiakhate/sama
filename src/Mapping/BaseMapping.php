<?php
namespace App\Mapping;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\AgenceRepository;
use App\Repository\DocumentRepository;
use App\Repository\SocieteRepository;
use App\Repository\TypeDocumentRepository;
use App\Service\BaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class  BaseMapping {
    protected $SUCCESS_KEY = 'success';
    protected $TEL_1KEY = 'tel1';
    protected $VALIDITE_KEY = 'validite';
    protected $TEL_2KEY = 'tel2';
    protected $ERROR_KEY = 'error';
    protected $CODE_KEY = 'code';
    protected $ADRESSE_KEY = 'adresse';
    protected $MATRICULE_KEY = 'matricule';
    protected $DATA_KEY = 'data';
    protected $AVATAR_KEY = 'avatar';
    protected $MESSAGE_KEY = 'message';
    protected $DIPLOME_KEY="diplome";
    protected $DATENAISANCE_KEY="datedenaissance";
    protected $LIEUNAISSANCE_KEY="lieudenaissance";
    protected $NCNI_KEY="ncni";
    protected $SEXE_KEY="sexe";
    protected $STRUCTURE_KEY="structure";
    protected $CATEGORIE_KEY="categorie";
    protected $AGENCE_KEY="agence";
    protected $SITMAT_KEY="sitmat";
    protected $UNIVERSITE_KEY="universite";
    protected $PROFESSION_KEY="profession";
    protected $SALAIREBRUT_KEY="salaire_brut";
    protected $LIBELLE_KEY="libelle";
    protected $DIRECTION_KEY="direction";
    protected $POLE_KEY="pole";
    protected $BU_KEY="bu";
    protected $SERVICE_KEY="service";
    protected $DEPARTEMENT_KEY="departement";
    protected $CODE_101 = '101';
    protected $CODE_102 = '102';
    protected $CODE_103 = '103';
    protected $CODE_104 = '104';
    protected $CODE_105 = '105';
    protected $CODE_106 = '106';
    protected $CODE_107 = '107';
    protected $CODE_108 = '108';
    protected $CODE_110 = '110';
    protected $CODE_200 = '200';
    protected $CODE_501 = '501';
    protected $CODE_502 = '502';
    protected $CODE_511 = '511';
    protected $CODE_515 = '515';
    protected $CODE_516 = '516';
    protected $CODE_517 = '517';
    protected $ID_KEY = 'id';
    protected $NOM_KEY = 'nom';
    protected $PRENOM_KEY = 'prenom';
    protected $EMAIL_KEY = 'email';
    protected $USERNAME_KEY = 'username';
    protected $TELEPHONE_KEY = 'telephone';
    protected $PROFIL_KEY = 'profil';
    protected $STATUS_FALSE = false;
    protected $STATUS_TRUE = true;
    protected $validator;
    protected $serializer;
    protected $encoder;
    protected $mailer;
    protected $baseService;
    protected $em;
    public function __construct(\Swift_Mailer $mailer,BaseService $baseService,EntityManagerInterface $em,ValidatorInterface $validator,SerializerInterface $serializer,UserPasswordEncoderInterface $encoder)
    {
        $this->validator=$validator;
        $this->serializer=$serializer;
        $this->encoder=$encoder;
        $this->mailer=$mailer;
        $this->baseService = $baseService;
        $this->em =$em;
    }
    public function sendMail($email,$password){
        $message = (new \Swift_Message())
            ->setFrom(array('orange@orange.sn'=>'API BO WIDO OGC'))
            ->setTo($email)
            // ->setBcc($bcc)
            ->setSubject("Données de connexion")
            //   ->attach(\Swift_Attachment::fromPath($chemin))
            ->setBody('Login: '.$email.' Password: '.$password);
        $this->mailer->send($message);
    }
    public function manageErrors($entity){
        $errors = $this->validator->validate($entity);
        if ($errors->count()>0){
            $err = json_decode( $this->serializer->serialize($errors, 'json'),true);
            return  array("code"=>500,"status"=>false,"message"=>$err['detail']);
        }
        return 'ok';
    }
}
