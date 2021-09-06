<?php

namespace App\Controller;

use App\Entity\Critic;
use App\Form\CriticType;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use SpotifyWebAPI\SpotifyWebAPI;
use App\Repository\CriticRepository;
use App\Repository\CommentaryRepository;
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
    public function index(CriticRepository $criticRepository, SpotifyWebAPI $api): Response
    {
        
        $critics = $criticRepository->findAll();

        // Récupère les ids des albums
        $albumsId = array_unique(array_map(function ($critic) {
            return $critic->getAlbumId();
        }, $critics));

        // Récupère les albums correspondants aux ids
        $results = $api->getAlbums($albumsId);

        // Création d'un tableau id album => objet album
        $albums = array_combine($albumsId, $results->albums);

        // Ajout d'une propriété album contenant l'objet correspondant à l'id de l'album
        array_walk($critics, function ($critic, $key) use ($albums) {
            $critic->album = $albums[$critic->getAlbumId()];
        });
        
        return $this->render('critics/critics_index.html.twig', [
            'critics' => $critics,
            'album' => $albums
        ]);
    }

    /**
     * @Route("/critic/{id}", name="view_critic")
     */
    public function show(SpotifyWebAPI $api, Critic $critic, CommentaryRepository $commentaryRepository, Request $request, EntityManagerInterface $manager):Response
    {

     
        $album = $api->getAlbum($critic->getAlbumId());
        $comment = new Commentary();
        $formulaire = $this->createForm(CommentaryType::class, $comment);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $comment->setCritic($critic);
            $comment->setUser($this->getUser());  

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute("view_critic", [
                'id'=> $critic->getId()
            ]);
        }

        return $this->render('critics/view_critic.html.twig', [
            'album' => $album,
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
