<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $formRegistration = $this->createForm(RegistrationType::class, $user);

        $formRegistration->handleRequest($request);

        dump($request);

        if ($formRegistration->isSubmitted() && $formRegistration->isValid()):

            $hash = $encoder->encodePassword($user, $user->getPassword());
            dump($hash);
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Félicitation !! Votre compte a bien été créé. Vous pouvez à présent vous connecter!");

            return $this->redirectToRoute('login');


        endif;


        return $this->render('security/inscription.html.twig', [
            'formRegistration' => $formRegistration->createView()
        ]);

    }


        /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
