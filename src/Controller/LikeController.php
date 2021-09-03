<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Critic;
use App\Entity\Commentary;
use App\Repository\LikeRepository;
use App\Repository\CriticRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LikeController extends AbstractController
{
    /**
     * @Route("/like/critics/{id}", name="like")
     */
    public function likeCritic(Critic $critic, UserInterface $user, EntityManagerInterface $manager, LikeRepository $likeRepo, Request $request): Response
    {
        $likeContent = $request->request->get('like');
        $isLike = $likeContent === 'like';

        $like = $likeRepo->findOneBy([
            'user' => $user, 
            'critic' => $critic
        ]);

        if($like)
        {
            if ($like->getValue() == $isLike) {
                $manager->remove($like);
                $like = null;
            } else {
                $like->setValue($isLike);
            }

            $manager->flush();
        }else {
            $like= new Like();
            $like->setUser($user);
            $like->setCritic($critic);
            $like->setValue($isLike);
            $manager->persist($like);
            $manager->flush();

            $critic->addLike($like);
        }

        $donnees= [
            'critic' => $critic,
            'like' => $like
        ];

        return $this->render('partials/like_critics.html.twig', $donnees);
    }

    /**
     * @Route("/like/commentary/{id}", name="like_commentary")
     */
    public function likeCommentary(Commentary $commentary, UserInterface $user, EntityManagerInterface $manager, LikeRepository $likeRepo, Request $request): Response
    {
        $likeContent = $request->request->get('like');
        $isLike = $likeContent === 'like';

        $like = $likeRepo->findOneBy([
            'user' => $user, 
            'commentary' => $commentary
        ]);


        if($like)
        {
            if ($like->getValue() == $isLike) {
                $manager->remove($like);
                $like = null;
            } else {
                $like->setValue($isLike);
            }

            $manager->flush();

        }else {
            $like= new Like();
            $like->setUser($user);
            $like->setCommentary($commentary);
            $like->setValue($isLike);

            $manager->persist($like);
            $manager->flush();
            
            $commentary->addLike($like);
        }

        $donnees= [
            'commentary'=> $commentary,
            'like' => $like
        ];

        return $this->render('partials/like_commentary.html.twig', $donnees);
    }
}
