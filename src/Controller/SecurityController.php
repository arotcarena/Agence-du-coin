<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Entity\UserRegistration;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @var UserRepository $repository
     */
    private $repository;

    
    private $em;


    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    #[Route('/registration', name: 'registration')]
    public function registration(UserPasswordHasherInterface $passwordHasher, Request $request):Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            if($_POST['password_confirm'] === $user->getPassword())
            {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword)
                    ->setRoles(['ROLE_USER']);
                $this->em->persist($user);
                $this->em->flush();
                $this->addFlash('success', 'Vous êtes inscrit sur notre site');
                return $this->redirectToRoute('login');
            }
            $class = 'is-invalid';
        }
        return $this->render('pages/security/registration.html.twig', ['form' => $form->createView(), 'class' => $class ?? '']); 
    }
    
}
