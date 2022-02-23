<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder, MailerInterface $mailer): Response
    {

        $user=new User();
        $form=$this->createForm(RegistrationFormType::class,$user,['attr' => ['class' => 'sign-up-form']]);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $user->setRoles("ROLE_USER");
            $user->setIsVerified(false);
            $manager->persist($user);
            $manager->flush();
            $code=md5(uniqid(),false);
            $request->getSession()->set("code",$code);
            $request->getSession()->set("userId",$user->getId());
            $mail=(new TemplatedEmail())->
            from("mohamedali.gatri@esprit.tn")
                ->to($user->getEmail())
                ->subject("Account Verification")
                ->htmlTemplate('EmailTemplate.html.twig')
                ->context([
                    'username'=>$user->getUsername(),
                    'code' => $code,
                ]);
            $mailer->send($mail);

            return $this->redirectToRoute("verify");
        }
        return $this->render('/user/index.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/check_login", name="check_login")
     */
   public function login(){
   }

    /**
     * @route("/test", name="test")
     */
    public function test(){
        $roles=$this->getUser()->getRoles();
        if (in_array("ROLE_ADMIN",$roles)){
            return $this->redirectToRoute('admin');
        }else{
            return $this->redirectToRoute("userFront");
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}

    /**
     * @Route("/verification", name="verify")
     */
    public function verify(Request $request,UserRepository $repository){
        $manager=$this->getDoctrine()->getManager();
        $ourCode=$request->getSession()->get("code");
        if ($request->getMethod() =="POST"){
            if ($request->get("verificationCode")==$ourCode){
                $id=$request->getSession()->get("userId");
                $user=$repository->find($id);
                $user->setIsVerified(true);
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute("login");
            }
        }
        return $this->render('/user/Verification.html.twig');
    }
}
