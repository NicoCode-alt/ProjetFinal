<?php

namespace App\Controller;

use App\Entity\Critic;
use App\Form\CriticType;
use App\Repository\CriticRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CriticsController extends AbstractController
{
    /**
     * @Route("/", name="critics_index")
     */
    public function index(CriticRepository $criticRepository): Response
    {
        return $this->render('critics/critics_index.html.twig', [
            'critics' =>$criticRepository->findAll()
        ]);
    }

    /**
     * @Route("/critic/{id}", name="view_critic")
     */
    public function show(Critic $critic):Response
    {
        // $commentaire = new commentaire();
        // $formulaire = $this->createForm(CommentaireType::class, $commentaire);

        // $formulaire->handleRequest($request);

        // if($formulaire->isSubmitted() && $formulaire->isValid()){
        //     $commentaire->setCreatedAt(new \DateTime());
        //     $commentaire->setLieu($critic);
        //     $commentaire->setAuthor($this->getUser());  

        //     $manager->persist($commentaire);
        //     $manager->flush();

        //     return $this->redirectToRoute("show_voyage", ['id'=>$critic->getId()]);
        // }

        return $this->render('critics/view_critic.html.twig', [
            'critic' => $critic,
            // 'commentaires' => $com->findAll(),
            // 'formulaire' => $formulaire->createView()
        ]);
    }

     /**
     * @Route("/critic/create", name="create_critic", priority=1)
     * @Route("/critic/edit/{id}", name="edit_critic", priority=1)
     */
    public function createCritic(Request $request, EntityManagerInterface $manager, Critic $critic=null):Response
    {
        $modeEdition = true;

        if(!$critic){
            $critic = new Critic();
            $modeEdition = false;
        }

        $formulaire = $this->createForm(CriticType::class, $critic);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            if(!$modeEdition)
            {
                $critic->setCreatedAt(new \DateTime());
                
            }
            
            $manager->persist($critic);
            $manager->flush();

            return $this->redirectToRoute('view_critic', ['id'=>$critic->getId()]);
        }

        return $this->render('critics/create_critic.html.twig',[
            "formulaire" => $formulaire->createView(),
            "modeEdition" => $modeEdition
        ]);
    }

}
