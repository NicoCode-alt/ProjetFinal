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
        $dislikeContent = $request->request->get('dislike');

        if( $critic->isLikedBy($user))
        {
            $like = $likeRepo->findOneBy([
                'user' => $user, 
                'critic' => $critic]);
            $manager->remove($like);
            $manager->flush();

            $liked = false;
        }else {
            $like= new Like();
            $like->setUser($user);
            $like->setCritic($critic);

            // if ($likeContent === 'like') {
            //     $like->setValue($likeContent === 'like');
            //  } else {
            //     $like->setValue($dislikeContent === 'dislike');
            //  }

             $like->setValue($likeContent === 'like');
            $manager->persist($like);
            $manager->flush();
            
            $liked = true;
        }

        $donnees= [
            'nombreLikes' => $likeRepo->count([
                'critic'=>$critic
            ]),
            'liked'=> $liked,
            'like' => $likeContent,
        ];

        return $this->json($donnees, 200);
    }

    /**
     * @Route("/like/commentary/{id}", name="like_commentary")
     */
    public function likeCommentary(Commentary $commentary, UserInterface $user, EntityManagerInterface $manager, LikeRepository $likeRepo, Request $request): Response
    {
        $likeContent = $request->request->get('like');
        $isLike = $likeContent === 'like';

        if( $commentary->isLikedBy($user))
        {
            $like = $likeRepo->findOneBy([
                'user' => $user, 
                'commentary' => $commentary
            ]);

            if ($like->getValue() == $isLike) {
                $manager->remove($like);
            } else {
                $like->setValue($isLike);
            }
            
            $manager->flush();

            $liked = false;
        }else {
            $like= new Like();
            $like->setUser($user);
            $like->setCommentary($commentary);
            $like->setValue($isLike);
            $manager->persist($like);
            $manager->flush();
            
            $liked = true;
        }

        $donnees= [
            'nombreLikes' => $likeRepo->count([
                'commentary'=>$commentary
            ]),
            'liked'=> $liked,
            'like' => $likeContent
        ];

        return $this->json($donnees, 200);
    }
}
