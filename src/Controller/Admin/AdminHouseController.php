<?php
namespace App\Controller\Admin;

use App\Entity\House;
use App\Form\HouseType;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AdminHouseController extends AbstractController
{
    /**
     * @var HouseRepository $repository
     */
    private $repository;

    
    private $em;


    public function __construct(HouseRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin_houses_index")
     */
    public function index():Response
    {
        $houses = $this->repository->findAll();  
        return $this->render('pages/admin/houses/index.html.twig', ['houses' => $houses]);
    }
    /**
     * @Route("/admin/biens/editer-un-bien/{id}", name="admin_houses_edit")   
     */
    public function edit(House $house, Request $request, CacheManager $cacheManager, UploaderHelper $helper):Response     //injection de $house(symfony voit l'id dans l'url et récupère la house correspondante)
    {
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            
            if($house->getImageFile() instanceof UploadedFile)
            {
                $cacheManager->remove($helper->asset($house, 'imageFile'));
            }

            $this->em->flush();
            $this->addFlash(
                'success',
                'Le bien a été modifié !'
            );
            return $this->redirectToRoute('admin_houses_index');
        }
        return $this->render('pages/admin/houses/edit.html.twig', [
            'form' => $form->createView()           // 2 MANIERES DIFF DE FAIRE
        ]);
    }
    /**
     * @Route("/admin/biens/ajouter-un-nouveau-bien", name="admin_houses_new")   
     */
    public function new(Request $request):Response          // Request c'est en gros $_POST 
    {
        $house = new House();
        $form = $this->createForm(HouseType::class, $house);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $this->em->persist($house);
            $this->em->flush();
            $this->addFlash(
                'success',
                'Le bien a été ajouté !'
            );
            return $this->redirectToRoute('admin_houses_index');
        }
        return $this->renderForm('pages/admin/houses/new.html.twig', [                 // 2 MANIERES DIFF DE FAIRE
            'form' => $form
        ]);
    }
    /**
     * @Route("/admin/biens/supprimer-un-bien/{id}", name="admin_houses_delete")  
     */
    public function delete(House $house, Request $request):Response     
    {
        if($this->isCsrfTokenValid('delete'.$house->getId(), $request->get('_token')))   // vérifie le token passé en post dans le formulaire  :  csrf_token('delete' ~ house.id)
        {
            $this->em->remove($house);
            $this->em->flush();
            $this->addFlash(
                'success',
                'Le bien a été supprimé !'
            );
        }
        return $this->redirectToRoute('admin_houses_index');   
    }
}