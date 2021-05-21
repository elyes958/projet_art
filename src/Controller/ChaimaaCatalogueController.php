<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\CategorieRepository;
use App\Repository\OeuvreRepository;

/**
 * @IsGranted("ROLE_USER")
 */
class ChaimaaCatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue/{categorieId}", name="catalogue", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository, OeuvreRepository $oeuvreRepository, $categorieId = 0): Response
    {
    	if($categorieId == 0){
    		$oeuvres = $oeuvreRepository->findAll();
    	}else{
    		$oeuvres = $oeuvreRepository->findBy(['categorie' => $categorieId]);
    	}
    	
        return $this->render('catalogue/list.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'oeuvres' => $oeuvres,
        ]);
    }
}
