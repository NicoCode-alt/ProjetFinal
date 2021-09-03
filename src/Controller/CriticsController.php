<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Critic;
use App\Form\CriticType;
use App\Form\CommentaryType;
use App\Repository\CommentaryRepository;
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
        $critics = $criticRepository->findAll();
        
        return $this->render('critics/critics_index.html.twig', [
            'critics' => $critics
        ]);
    }

    /**
     * @Route("/critic/{id}", name="view_critic")
     */
    public function show(Critic $critic, CommentaryRepository $commentaryRepository, Request $request, EntityManagerInterface $manager):Response
    {
        $comment = new Commentary();
        $formulaire = $this->createForm(CommentaryType::class, $comment);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $comment->setCritic($critic);
            $comment->setUser($this->getUser());  

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute("view_critic", ['id'=>$critic->getId()]);
        }

        return $this->render('critics/view_critic.html.twig', [
            'critic' => $critic,
            'commentaires' => $commentaryRepository->findAll(),
            'formulaire' => $formulaire->createView()
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
                $critic->setUser($this->getUser());
                
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
