<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AuthController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();

        $formulaire = $this->createForm(RegisterType::class, $user);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $hashedPass = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPass);

            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('auth/index.html.twig', [
            'formulaire' => $formulaire->createView()
        ] );
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response 
    {
        return $this->render("auth/login.html.twig");
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() 
    {
        
    }
}

