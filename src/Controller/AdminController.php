<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted(['IS_FULLY_AUTHENTICATED','ROLE_ADMIN']);
        return $this->render('/BackOffice/index.html.twig');
    }

    /**
     * @Route ("/admin/listUsers" , name="admin_listUsers")
     */
    public function listUsers(UserRepository $repository):Response{
        $listUsers=$repository->findAll();
        return $this->render('BackOffice/UserManagement/listUser.html.twig',[
            'listUsers'=>$listUsers,
        ]);
    }

    /**
     * @Route("/admin/addUser", name="admin_addUser")
     */
    public function addUser(UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user=new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted()){
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('admin_listUsers');
        }
        return $this->render('BackOffice/UserManagement/addUser.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/admin/updateUser/{id}", name="admin_updateUser")
     */
    public function updateUser(UserRepository $userRepository,int $id, Request $request, UserPasswordEncoderInterface $encoder):Response
    {
        $user=$userRepository->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted()){
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->flush();
            return $this->redirectToRoute('admin_listUsers');
        }

        return $this->render('BackOffice/UserManagement/updateUser.html.twig',[
            'form'=>$form->createView(),
        ]);

    }

    /**
     * @Route("/admin/deleteUser/{id}", name="admin_deleteUser")
     */
    public function deleteUser(UserRepository $repository, int $id)
    {
        $user=$repository->find($id);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute("admin_listUsers");

    }


}
