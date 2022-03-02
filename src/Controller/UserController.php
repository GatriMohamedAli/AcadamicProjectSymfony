<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function test(Request $request){
        $request->get("Agent");
        if (strcmp($request->get("Agent"),"mobile")==0){
            return new Response(json_encode("Logged in"));
        }
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
    /**
     * @Route("/profile",name="profile")
     */
    public function profile(UserRepository $repository){
        $userInterface=$this->getUser();
        $userInfo=$repository->findOneByUsername($userInterface->getUsername());
        if (in_array("ROLE_ADMIN",$userInterface->getRoles())){
            return $this->render('/BackOffice/profile.html.twig',[
                'user'=>$userInfo
            ]);
        }else{
            return $this->render('/FrontOffice/profile.html.twig',[
                'user'=>$userInfo
            ]);
        }
    }
    /**
     * @Route("/profilemodify", name="modify_profile")
     */
    public function modifyProfile(Request $request,UserRepository $repository,EntityManagerInterface $manager){
        $userInterface=$this->getUser();
        $userInfo=$repository->findOneByUsername($userInterface->getUsername());
        $form=$this->createForm(ProfileFormType::class,$userInfo);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $manager->flush();
            $userInfo->setImageFile(null);
            return $this->redirectToRoute('profile');
        }
        if (in_array("ROLE_ADMIN",$userInterface->getRoles())){
            return $this->render('/BackOffice/modifyProfile.html.twig',[
                'form'=>$form->createView()
            ]);
        }else{
            return $this->render('/FrontOffice/modifyProfile.html.twig',[
                'form'=>$form->createView()
            ]);
        }
    }

    /**
     * @Route("/profileview/{id}", name="view_Profile")
     */
    public function showProfile(User $userInfo){
        return $this->render('/BackOffice/profile.html.twig',[
            'user'=>$userInfo
        ]);
    }
    /**
     * @Route("/loginJson", name="loginJson", methods={"POST"})
     */
    public function loginJson(Request $request): Response
    {
        $user = $this->getUser();

        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Route("/searchUser" , name="search_user")
     */
    public function search(Request $request,UserRepository $userRepository){
        $allusers=$userRepository->findAll();
        $finalList=[];
        foreach ($allusers as $user){
            if($user->getUsername()!=$this->getUser()->getUsername()){
                array_push($finalList,$user);
            }
        }
        $crit=$request->get('crit');
        $filtredusers=$userRepository->findBySomething($crit);
//        array_push($finalList,$filtredusers);
        $result=array(
            $finalList,$filtredusers
        );
        return $this->json($result);
    }

    /**
     * @Route("/signupJson", name="signupJson")
     */
    public function signupJson(Request $request,UserPasswordEncoderInterface $encoder, MailerInterface $mailer): Response
    {
        $user=new User();
        $form=$this->createForm(RegistrationFormType::class,$user,['attr' => ['class' => 'sign-up-form']]);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $manager=$this->getDoctrine()->getManager();
        if($form->isValid()){
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

            return $this->json($user);
        }
        return $this->render('/user/index.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
}
