<?php
namespace App\Model;

use App\Mapping\UserMapping;
use App\Service\BaseService;
use App\Mapping\AgenceMapping;
use App\Mapping\ProfilMapping;
use App\Mapping\DemandeMapping;
use Doctrine\ORM\EntityManager;
use App\Mapping\ObjectifMapping;
use App\Mapping\StructureMapping;
use App\Mapping\InterimaireMapping;
use Symfony\Component\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseManager{
    protected $SUCCESS_KEY = 'success';
    protected $LIMIT ;
    protected $ERROR_KEY = 'error';
    protected $CODE_KEY = 'code';
    protected $DATA_KEY = 'data';
    protected $AVATAR_KEY = 'avatar';
    protected $MESSAGE_KEY = 'message';
    protected $TOTAL_KEY = 'total';
    protected $tenant_key = 'tenant';
    protected $em = null;
    protected $code_key = 'code';
    protected $success_key = 'success';
    protected $STATUS_TRUE=true;
    protected $data_key = 'data';
    protected $CODE_200 = 200;
    protected $serializer;
    protected $validator;
    protected $mailer;
    protected $baseService;
    /**
     * @param BaseService $baseService
     * @param MailerInterface $mailer
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct( BaseService $baseService, \Swift_Mailer $mailer,SerializerInterface $serializer,ValidatorInterface $validator,EntityManagerInterface $em){
        $this->em =$em;
        $this->LIMIT = getenv('LIMIT');
        $this->baseService=$baseService;
        $this->mailer=$mailer;
        $this->serializer=$serializer;
        $this->validator=$validator;
    }
    public function paginateData($data,$pager,$pagerNext,$totalItems){
          $currentPage= $data['offset']==0?$page=1:$page=($data['offset']/$_ENV["ITEMS_PER_PAGE"])+1;
       // $nextPage=(count($totalItems)-(count($pager))>0&&count($totalItems)-(count($pager))<$_ENV["ITEMS_PER_PAGE"])?$currentPage+1:$currentPage;
        $nextPage=$pagerNext>0?$currentPage+1:null;
        $previousPage=$currentPage==1?null:$currentPage-1;
       // $previousPage==1?$previousPage=null:$previousPage=$previousPage-1;
        return array("limit"=>$data['limit'],"offset"=>$data['offset'],"previous"=>$previousPage,"next"=>$nextPage,"current"=>$currentPage,"totalNext"=>$pagerNext,"totalPages"=>ceil($totalItems/$_ENV["ITEMS_PER_PAGE"]));
    }
    public function manageErrors($entity){
        $errors = $this->validator->validate($entity);
        if ($errors->count()>0){
            $err = json_decode( $this->serializer->serialize($errors, 'json'),true);
            return  array("code"=>500,"status"=>false,"message"=>$err['detail']);
        }
        return 'ok';
    }
    public function calculTotalNext($totalItems,$page){
        $nextP=0;
        $restant=(($totalItems/$_ENV["ITEMS_PER_PAGE"])-$page);
        if ($restant>=1){
            $nextP=10;
        }elseif ($restant<1 &&$restant>0){
            $nextP=  round(10*$restant);

        }elseif ($restant<=0){
            $nextP=0;
        }
        return $nextP;
    }
}
