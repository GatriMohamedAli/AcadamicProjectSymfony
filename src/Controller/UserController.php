<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $user=new User();
        $form=$this->createForm(RegistrationFormType::class,$user,['attr' => ['class' => 'sign-up-form']]);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('/user/index.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/check_login", name="check_login")
     */
   public function login(){}

    /**
     * @route("/test", name="test")
     */
    public function test(){
        $roles=$this->getUser()->getRoles();
        if (in_array("ROLE_ADMIN",$roles)){
            return $this->redirectToRoute('admin');
        }
        return $this->render('/user/test.html.twig',[
            'name'=>$roles,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}
}
