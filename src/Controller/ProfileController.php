<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
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

    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        return $this->render('pages/profile/index.html.twig', [
            'current_menu' => 'profile'
        ]);
    }
}
