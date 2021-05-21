<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Form\OeuvreType;
use App\Repository\OeuvreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/oeuvre")
 * @IsGranted("ROLE_ADMIN")
 */
class ChaimaaOeuvreController extends AbstractController
{


    /**
     * @Route("/new", name="oeuvre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // on récupère ici toutes les données de l'input name="image"
            $imageFile=$form->get('image')->getData();

            if ($imageFile) {

                $nomImage = date("YmdHis")."-".uniqid()."-".$imageFile->getClientOriginalName();
                $imageFile->move(
                   $this->getParameter('images_directory'),
                   $nomImage
               );

                $oeuvre->setImage($nomImage);
            }

            $entityManager->persist($oeuvre);
            $entityManager->flush();

            return $this->redirectToRoute('oeuvre_index');
        }

        return $this->render('admin/oeuvre/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="oeuvre_show", methods={"GET"})
     */
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('admin/oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="oeuvre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Oeuvre $oeuvre): Response
    {
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if($imageFile)
            {
                $nomImage = date("YmdHis") . "-" . uniqid() . "-" . $imageFile->getClientOriginalName();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $nomImage
                );

                $oeuvre->setImage($nomImage);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('oeuvre_index');
        }

        return $this->render('admin/oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="oeuvre_delete", methods={"POST"})
     */
    public function delete(Request $request, Oeuvre $oeuvre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($oeuvre);

            if(!empty($oeuvre->getImage() ))
            {
                unlink($this->getParameter('images_directory') .'/'. $oeuvre->getImage());
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('oeuvre_index');
    }
}
