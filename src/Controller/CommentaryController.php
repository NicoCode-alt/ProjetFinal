<?php

namespace App\Controller;

use App\Entity\Commentary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaryController extends AbstractController
{
    /**
     * @Route("/commentary/{id}", name="delete_commentary")
     */
    public function index(EntityManagerInterface $manager, Commentary $commentary): Response
    {
        $idComment = $commentary->getCritic()->getId();

        // if($user == $commentary->getAuthor()){

            $manager->remove($commentary);
            $manager->flush();
        // }

        return $this->redirectToRoute("view_critic", ['id'=>$idComment]);
        
    }
}
