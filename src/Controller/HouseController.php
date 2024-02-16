<?php
namespace App\Controller;

use App\Entity\House;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\HouseFilter;
use App\Form\HouseFilterType;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HouseController extends AbstractController
{
    /**
     * @var HouseRepository $repo
     */
    private $repository;

    
    private $em;


    public function __construct(HouseRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/biens", name="houses_index")
     */
    public function index(PaginatorInterface $paginator, Request $request):Response
    {
        //on traite le formulaire et on entre les données reçues dans $houseFilter
        $houseFilter = new HouseFilter();
        $form = $this->createForm(HouseFilterType::class, $houseFilter, [
            'action' => $this->generateUrl('houses_index'),   //pour appeler toujours la page=1 en cas de soumission du formulaire
        ]);
        $form->handleRequest($request);
        /*
        if ($form->isSubmitted() && !$form->isValid()) // si le formulaire est invalide on vide les données de filtrage : PB ON PASSE LA QUAND ON CHANGE DE PAGE ET DONC CA VIDE TOUT LES FILTRES
        {
            $houseFilter = new HouseFilter();
        }
        */
        //on passe $houseFilter au repository qui nous renvoie un query que l'on passe au paginator pour récupérer les maisons
        $houses = $paginator->paginate($this->repository->findAllNotSoldQuery($houseFilter), 
                                    $request->query->getInt('page', 1), 
                                    6); 
        return $this->render('pages/houses/index.html.twig', ['current_menu' => 'houses_index', 'houses' => $houses, 'form' => $form->createView()]);
    }
    /**
     * @Route("/biens/{slug}-{id}", name="houses_show")   //voir route.yaml pour la regex du slug (requirements)
     */
    public function show(House $house, string $slug, Request $request, ContactNotification $notification):Response     //injection de $house(symfony voit l'id dans l'url et récupère la house correspondante)
    {
        if($house->getSlug() !== $slug)
        {
            return $this->redirectToRoute('houses_show', ['id' => $house->getId(), 'slug' => $house->getSlug()], 301);   // 301 = redirection permanente
        }
        if(isset($_GET['contact']))
        { 
            $contact = new Contact();
            $contact->setHouse($house);
            $form = $this->createForm(ContactType::class, $contact);

            $form->handleRequest($request);
            if($form->isSubmitted() AND $form->isValid())
            {
                $this->addFlash('success', 'Votre message a bien été envoyé !');
                $notification->notify($contact);
                return $this->redirectToRoute('houses_show', ['id' => $house->getId(), 'slug' => $house->getSlug()]);
            } 
            return $this->render('pages/houses/show.html.twig', [
                'house' => $house,
                'form' => $form->createView(),
                'contact' => 'contact',
                'button_target' => $this->generateUrl('houses_show', ['id' => $house->getId(), 'slug' => $house->getSlug()]),
                'button_class' => 'btn-outline-primary'
            ]);
        }
        return $this->render('pages/houses/show.html.twig', [
            'house' => $house,
            'button_target' => $this->generateUrl('houses_show', ['id' => $house->getId(), 'slug' => $house->getSlug()]).'?contact=1',
            'button_class' => 'btn-primary'
        ]);
    }
}