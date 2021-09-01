<?php

namespace App\Controller;

use App\Entity\Commentary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentaryController extends AbstractController
{
    /**
     * @Route("/commentary/{id}", name="delete_commentary")
     */
    public function index(EntityManagerInterface $manager, Commentary $commentary, UserInterface $user): Response
    {
        $idComment = $commentary->getCritic()->getId();

        if($user == $commentary->getUser());

            $manager->remove($commentary);
            $manager->flush();
        

        return $this->redirectToRoute("view_critic", ['id'=>$idComment]);
        
    }
}
