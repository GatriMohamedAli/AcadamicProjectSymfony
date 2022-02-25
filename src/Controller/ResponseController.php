<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Responses;
use App\Form\ResponseFormType;
use App\Repository\ReclamationRepository;
use App\Repository\ResponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResponseController extends AbstractController
{
    /**
     * @Route("/response", name="response")
     */
    public function index(ResponseRepository $repository): Response
    {
        $listResponses=$repository->findAll();
        return $this->render('response/index.html.twig', [
            'listResponses' => $listResponses,
        ]);
    }

    /**
     * @Route("responseadd/{id}", name="add_response")
     */
    public function addResponse(ReclamationRepository $repository,Request $request,int $id): Response
    {
        $response=new Responses();
        $rec=$repository->find($id);

        $form=$this->createForm(ResponseFormType::class,$response);
        //dd($form->getData());
        $form->get('reclamation')->setData($rec); //['rec_value' => $rec->getTitle()]
        $form->handleRequest($request);

        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($response);
            $manager->flush();
            return $this->redirectToRoute('response');
        }

        return $this->render('response/addReponse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("responseDelete/{id}",name="delete_response")
     */
    public function deleteResponse(int $id,ResponseRepository $repository):Response
    {
        $response=$repository->find($id);
        $manager=$this->getDoctrine()->getManager();
        $manager->remove($response);
        $manager->flush();
        return $this->redirectToRoute('response');

    }
    /**
     * @Route("responseUpdate/{id}",name="update_response")
     */
    public function updateReclamation(ResponseRepository $repository,int $id,Request $request):Response
    {
        $response=$repository->find($id);
        $form=$this->createForm(ResponseFormType::class,$response);
        $form->handleRequest($request);
        $manager=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            $manager->flush();
            return $this->redirectToRoute('response');
        }
        return $this->render('response/updateResponse.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/responseUser/{id}", name="responseview")
     */
    public function viewResponse(ResponseRepository $repository,Reclamation $reclamation): Response
    {
        $listResponses=$repository->findAll();
        $finalList=[];
        foreach ($listResponses as $respons){
            $listRec=$respons->getReclamation();
            foreach($listRec->getIterator() as $item) {
                if ($item->getId()==$reclamation->getId()){
                    array_push($finalList,$respons);
                }
            }
        }
        return $this->render('FrontOffice/reclamations/viewResponse.html.twig', [
            'listResponses' => $finalList,
        ]);
    }
}
