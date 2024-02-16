<?php
namespace App\Controller;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index():Response
    {

        return new Response($this->twig->render('pages/contact.html.twig', [
            'current_menu' => 'contact'
        ]));
    }
}