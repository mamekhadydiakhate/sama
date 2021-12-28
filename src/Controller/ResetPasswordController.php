<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Mime\Address;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Templating\EngineInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use App\Annotation\QMLogger;
use App\Entity\HistoriquePass;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    const MESSAGE_SEND="Un email vous a été envoyé par mail, merci de cliquer sur le lien afin de réinitialiser votre mot de passe. Le lien expire dans %s heure.
    Si vous ne recevez pas d'email, merci de vérifier votre spam, ou de réessayer encore.";
    private $resetPasswordHelper;
	protected $mailer;
    protected $em;

    public function __construct(EntityManagerInterface $em, ResetPasswordHelperInterface $resetPasswordHelper,EngineInterface $templating,\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->resetPasswordHelper = $resetPasswordHelper;
		$this->templating = $templating;
        $this->em=$em;
    }

    /**
     * Display & process form to request a password reset.
     * Demande de réinitialisation
     * @Route("/reinitialisationMotDePass", name="app_forgot_password_request")
     * @QMLogger(message="Demande de reinitialisation mot de pass")
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        //exit(var_dump(getenv('BASE_URL')));
        $values=$request->request->all();
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        $form->submit($values); 
        if ($form->isSubmitted()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }
        //rediriger vers le formulaire de réinitialisation
        return new JsonResponse(array("code"=>502,"status"=>false,"message"=>"formulaire invalide"));
    }

    /**
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/check-email", name="app_check_email")
     */
    public function checkEmail(): Response
    {
        // We prevent users from directly accessing this page
        if (!$this->canCheckEmail()) {
            //rediriger vers le formulaire de réinitialisation
            return new JsonResponse(array("code"=>502,"status"=>false,"message"=>"email invalid"));
        }
        $tokenDuration=$this->resetPasswordHelper->getTokenLifetime()/3600;
        $message=sprintf(self::MESSAGE_SEND,$tokenDuration);
        return new JsonResponse(array("code"=>200,"status"=>true,"message"=>$message));
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Rest\Post("/reset/{token}", name="app_reset_password")
     * @QMLogger(message="Reinitialisation mot de pass")
     */
    public function reset(Request $request,SerializerInterface $serializer,ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        $values=$request->request->all();
        $plainPassword=(isset($values["plainPassword"]) && $values["plainPassword"])?$values["plainPassword"]:null;
         if (!$this->getTokenFromSession() && $token) {
            $this->storeTokenInSession($token);
        }
        $token=$this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('Token introuvable.');
        }
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            return new JsonResponse(array("code"=>502,"status"=>false,"message"=>$e->getReason()));
        }
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        $form->submit($values);
        if(!isset($values["password"]) && $values["password"]==null) {
           return new JsonResponse(array("code"=>500,"status"=>false,"message"=>"veuillez-saisir votre mot de passe"));
        }
        if(strcmp($values["password"],$plainPassword)!==0){
            $message="Les deux mots de passe saisis sont différents";
            return new JsonResponse(array('status' => 502, 'status'=> false, 'message' =>$message));
        }
        if ($form->isSubmitted()){
            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $values["password"]
            );
            $passes=$this->em->getRepository(HistoriquePass::class)->findBy(array("user"=>$user),array("date"=>"DESC"),3);
            if($passes){
                foreach ($passes as $passe) {
                    if(strcmp($encodedPassword,$passe->getPassword()) == 0){
                        $message="Vous ne devez pas saisir les trois derniers mots de passe utilisés";
                        return new JsonResponse(array('status' => 504, 'status'=> false, 'message' =>$message));
                    }
                }
            }
            $this->historisation($encodedPassword,$user);
            $this->resetPasswordHelper->removeResetRequest($token);
            $this->cleanSessionAfterReset();
            return new JsonResponse(array("code"=>200,"status"=>true,"message"=>"mot de passe réinitialisé avec succés"));
        }
        return new JsonResponse(array("code"=>502,"status"=>false,"message"=>$e->getReason()));
    }

    public function historisation($encodedPassword,$user){
        $histoPass=new HistoriquePass();
        $user->setPassword($encodedPassword);
        $user->setPasswordRequestedAt(new DateTime(date("Y-m-d H:i:s")));
        $user->setEnabled(1);
        $user->setLoginTentative(0);
        $histoPass->setPassword($encodedPassword);
        $histoPass->setDate(new DateTime(date("Y-m-d H:i:s")));
        $histoPass->setUser($user);
        $this->getDoctrine()->getManager()->persist($histoPass);
        $this->getDoctrine()->getManager()->flush();
    }

    private function processSendingPasswordResetEmail(string $emailFormData)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);
        $this->setCanCheckEmailInSession();
        if (!$user) {
            return new JsonResponse(array("code"=>501,"status"=>false,"message"=>"utilisateur inexistant"));
        }
        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            return new JsonResponse(array("code"=>504,"status"=>false,"message"=>$e->getReason()));
        }
        $tokenDuration=$this->resetPasswordHelper->getTokenLifetime()/3600;
        $message=sprintf(self::MESSAGE_SEND, $tokenDuration);
        $email= (new \Swift_Message('Réinitialisation mot de passe'))
                    ->setFrom(array('no-reply@orange-sonatel.com'=>'GDI SONATEL'))
                    ->setTo($user->getEmail())
                    ->setCc("ddiatou1@gmail.com")
                    ->setBody($this->templating->render('reset_password/email.html.twig',array(
                        'resetToken' => $resetToken,
                        'url'=>getenv('BASE_URL').$resetToken->getToken(),
                        'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
                    )))
                    ->setContentType("text/html")
                    //->attach(\Swift_Attachment::fromPath('/path/to/a/file.zip'))
                    ;
                $this->mailer->send($email);
                $tokenDuration=$this->resetPasswordHelper->getTokenLifetime()/3600;
                $message=sprintf(self::MESSAGE_SEND,$tokenDuration);
                return new JsonResponse(array("code"=>203,"status"=>true,"message"=>$message));
    }
}
