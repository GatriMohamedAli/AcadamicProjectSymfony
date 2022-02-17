<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationFormType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(ReclamationRepository $repository): Response
    {
        $listReclamations=$repository->findAll();
        return $this->render('reclamation/index.html.twig', [
            'listReclamations' => $listReclamations,
        ]);
    }

    /**
     * @Route("reclamationAdd",name="add_reclamation")
     */
    public function addReclamation(UserRepository $repository,Request $request,UserInterface $user):Response
    {
        $reclamation=new Reclamation();
        $form=$this->createForm(ReclamationFormType::class,$reclamation);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            //$user=$repository->findOneByUsername($this->getUser()->getUsername());
            $reclamation->setUser($user);
            $manager->persist($reclamation);
            $manager->flush();
            return $this->redirectToRoute('reclamation');
        }
        return $this->render('reclamation/addReclamation.html.twig',[
            'form'=>$form->createView(),
        ]);

    }
    /**
     * @Route("reclamationDelete/{id}",name="delete_reclamation")
     */
    public function deleteReclamation(int $id,ReclamationRepository $repository):Response
    {
        $reclamation=$repository->find($id);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($reclamation);
        $manager->flush();
        return $this->redirectToRoute('reclamation');

    }
    /**
     * @Route("reclamationUpdate/{id}",name="update_reclamation")
     */
    public function updateReclamation(ReclamationRepository $repository,int $id,Request $request):Response
    {
        $reclamation=$repository->find($id);
        $form=$this->createForm(ReclamationFormType::class,$reclamation);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            $manager->flush();
            return $this->redirectToRoute('reclamation');
        }
        return $this->render('reclamation/updateReclamation.html.twig',[
            'form'=>$form->createView(),
        ]);


    }
}
