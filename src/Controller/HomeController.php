<?php
namespace App\Controller;

use Twig\Environment;
use App\Repository\HouseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(HouseRepository $repository):Response
    {
        $houses = $repository->findLastsNotSold();
        return new Response($this->twig->render('pages/home.html.twig', ['houses' => $houses]));
    }
}