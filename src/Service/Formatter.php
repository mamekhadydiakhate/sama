<?php
namespace App\Service;

use Psr\Log\LoggerInterface;
use  Monolog\Formatter\FormatterInterface;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class Formatter implements FormatterInterface
{
    private $logger;
    /**
     * 
     * @param JWTTokenManagerInterface $jwtMexit
     * 
     */
    public function __construct(LoggerInterface $logger, JWTTokenManagerInterface $jwtManager) {
        $this->logger = $logger;
        $this->jwtM = $jwtManager;
    }
    private function JsonEncodeWithPassword($data) {
        foreach($data as $key => $value) {
            if(isset($data[$key]['_token'])) {
                unset($data[$key]['_token']);
            }
        }
        if(isset($data['password'])) {
            $data['password'] = md5($data['password']);
        }
        if(isset($data['plainPassword'])) {
            $data['plainPassword'] = md5($data['plainPassword']);
            //$data['plainPassword']['second'] = md5($data['plainPassword']['second']);
        }
        return json_encode($data);
    }

    public function format(array $record) {
        $container = $record['context']['container'];
        $perimetre=$record['message'];
        $token	= $container->get('security.token_storage')->getToken();
        try {
            $decodeT = $token==null?null: $this->jwtM->decode($token);
            $login 	= $decodeT ? $decodeT["username"] : 'inconnu';
          } catch (JWTDecodeFailureException $ex) {
            $login 	= 'inconnu';
                  // if no exception thrown then the token could be used
          }
        //$user	= $token ? $token->getUser() : null;
        $request= $container->get('request_stack')->getCurrentRequest();
        $DATE_ACTION=date("Y-m-d H:i:s");
        $TYPE_LOG="INFO";
        $CODE_SRC_LOG=$record['message'];
        $SERVICENAME="SUIVI-API";
        $IP_SRC_ACTION=$this->getUserIpAddr();
        $HOSTNAME_SRC_ACTION="732df7107b5f";
        $LOGIN=$login;
        $CODE_REF_ACTION_MATRICE="";
        $DESCRIPTION_REF_ACTION="";
        $DESCRIPTION_SRC_LOG="Application";
        $IP_DEST_ACTION=$request->getClientIp();
        $HOSTNAME_DEST_ACTION="732df7107b5f";
        $DATE_DEBUT_ACTION=date("Y-m-d H:i:s.v");
        $DATE_FIN_ACTION=date("Y-m-d H:i:s");
        $CODE_LOCALISATION_ACTION="";
        $DESCRIPTION_LOCALISATION_ACTION="";
        $DONNEESPOSTEES=$this->JsonEncodeWithPassword($request->request->all());
        $METHODE=$request->getMethod();
        $USERAGENT=$_SERVER['HTTP_USER_AGENT'];
        $uri=$_SERVER['REQUEST_URI'];
        $data=array();
       // $processThreadId=Thread::getCurrentThreadId();
        if(isset($record['context']['event']) && $record['context']['event']=='REQUEST'){
            $data=array(
                "timestamp"=>$DATE_DEBUT_ACTION,
                "log.level"=>$TYPE_LOG,
                "service.name"=>$SERVICENAME,
                "logger"=>"LoggingAspect",
                "message"=>array(
                    "event.type"=>"AUDIT",
                    "client.ip"=>$IP_DEST_ACTION,
                    "source.name"=>$IP_SRC_ACTION,
                    "user.name"=>$login,
                    "perimeter.description"=>$perimetre,
                    "http.request.body.content"=>$DONNEESPOSTEES,
                    "user_agent.original"=>$USERAGENT,
                    "url.path"=>$uri,
                    "http.request.method"=>$METHODE,
                    "process.thread.id"=>"20"
                )
            );
        }
        else if(isset($record['context']['event']) && $record['context']['event']=='RESPONSE' && isset($record['context']['response'])){
            $code=$record['context']['response']->getStatusCode();
            $content=json_decode($record['context']['response']->getContent(),true);
            $dataContent=isset($content["audit"])?($content["audit"]):null;
            $duration=$record['context']['duration'];
            $status=(int)$record['context']['response']->getStatusCode()==200 ? 'SUCCESS' : 'FAILED';
            $data=array(
                "@timestamp"=>$DATE_DEBUT_ACTION,
                "log.level"=>$TYPE_LOG,
                "service.name"=>$SERVICENAME,
                "logger"=>"LoggingAspect",
                "message"=>array(
                    "event.type"=>"AUDIT",
                    "client.ip"=>$IP_DEST_ACTION,
                    "source.name"=>$IP_SRC_ACTION,
                    "user.name"=>$login,
                    "perimeter.description"=>$perimetre,
                    "user_agent.original"=>$USERAGENT,
                    "url.path"=>$uri,
                    "http.request.method"=>$METHODE,
                    "http.response.status"=>$status,
                    "http.response.status_code"=>$code,
                    "http.response.content"=>$dataContent,
                    "event.duration"=>$duration,
                    "process.thread.id"=>"20",
                )
            );
        }
       
      /*   if(isset($record['context']['request_uri'])) {
            $line  = "";
        } else{
            $line = sprintf("%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%s", $DATE_ACTION, $TYPE_LOG, $CODE_SRC_LOG,$IP_SRC_ACTION,
                            $HOSTNAME_SRC_ACTION, $LOGIN, $CODE_REF_ACTION_MATRICE, $DESCRIPTION_REF_ACTION,$DESCRIPTION_SRC_LOG,$IP_DEST_ACTION,$HOSTNAME_DEST_ACTION,
                            $DATE_DEBUT_ACTION, $DATE_FIN_ACTION, $CODE_LOCALISATION_ACTION, $DESCRIPTION_LOCALISATION_ACTION, $DONNEESPOSTEES);
        } */
        //return $line.PHP_EOL;
        return json_encode($data).PHP_EOL.PHP_EOL;
    }


    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        // TODO: Implement formatBatch() method.
    }

    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
?>
