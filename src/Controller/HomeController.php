<?php

namespace App\Controller;

use App\Repository\OeuvreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(): Response
    {
      	return $this->render('home.html.twig');
    }

    /**
     * @Route("/oeuvre/index", name="oeuvre_index", methods={"GET"})
     */
    public function oeuvres(OeuvreRepository $oeuvreRepository): Response
    {
        return $this->render('admin/oeuvre/index.html.twig', [
            'oeuvres' => $oeuvreRepository->findAll(),
        ]);
    }
}
