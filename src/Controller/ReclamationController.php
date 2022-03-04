<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationFormType;
use App\Repository\ReclamationRepository;
use App\Repository\ResponseRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(ReclamationRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $listReclamations=$repository->findAll();

        if (in_array("ROLE_USER",$this->getUser()->getRoles())){
            $reclUser = [];
            foreach ($listReclamations as $rec) {
                $userId = $rec->getUser()->getUsername();
                if ($userId == $this->getUser()->getUsername()) {
                    array_push($reclUser, $rec);
                }
            }
            //$listReclamations=$reclUser;

            $listReclamations=$paginator->paginate($reclUser,$request->query->getInt('page',1),6);
            return $this->render('FrontOffice/reclamations/listReclamation.html.twig',[
                'listReclamations' => $listReclamations,
            ]);
    }

        $listAd=$listReclamations;
        //dd($listAd);
        $listReclamations=$paginator->paginate($listAd,$request->query->getInt('page',3),6);
        return $this->render('reclamation/index.html.twig', [
            'listReclamations' => $listReclamations,
        ]);
    }

    /**
     * @Route("reclamationAdd",name="add_reclamation")
     */
    public function addReclamation(UserRepository $repository,Request $request,UserInterface $user, HubInterface $hub):Response
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
            $update = new Update("https://example.com/reclamations", '{'
                .'"userId" : "'.$user->getUsername().'",'
                .'"reclId" :"'.$reclamation->getId().'"}');
            $hub->publish($update);
            return $this->redirectToRoute('reclamation');
        }
        if (in_array("ROLE_USER",$this->getUser()->getRoles())){
            return $this->render('FrontOffice/reclamations/addReclamation.html.twig',[
                'form'=>$form->createView(),
            ]);
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
        if (in_array("ROLE_USER",$this->getUser()->getRoles())){
            return $this->render('FrontOffice/reclamations/addReclamation.html.twig',[
                'form'=>$form->createView()
            ]);
        }
        return $this->render('reclamation/updateReclamation.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/viewReclamation/{id}", name="view_single_reclamtion")
     */
    public function viewSingleReclamation(int $id,ReclamationRepository $repository){
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $reclamation=$repository->find($id);
        return $this->render('reclamation/viewSingleReclamation.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route ("/search", name="search")
     */
    public function search(Request $request,ReclamationRepository $repository)
    {
//        dd(strpos("test test "," "));
        //dd(substr("test test",0,strpos("test test"," ")));
        $crits=explode(" ",$request->get("test"));
        $listReclamation=$repository->findByExampleField($crits);
        return $this->json($listReclamation);
    }

}
