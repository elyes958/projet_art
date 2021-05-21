<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Oeuvre;
use App\Entity\Commande;
use App\Form\OeuvreType;
use App\Repository\CommandeRepository;
use App\Repository\OeuvreRepository;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ChaimaaCommandeController extends AbstractController
{
    /**
     * @Route("/mes-commandes", name="mes_commandes", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function mesCommandes(CommandeRepository $repository): Response
    {
    	$commandes = $repository->findByUser($this->getUser());
    	
        return $this->render('mes_commandes.html.twig',[
            'commandes'=>$commandes
        ]);
    }
}
