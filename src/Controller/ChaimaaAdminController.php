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
use Swift_Image;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;


/**
 * @Route("/admin")
 */
class ChaimaaAdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/home.html.twig');
    }

    /**
     * @Route("/ajoutpanier/{id}", name="ajout_panier")
     */
    public function ajoutPanier($id,PanierService $panierService)
    {

        $panierService->add($id);


            return $this->redirectToRoute('catalogue');


    }

    /**
     * @Route("/retraitpanier/{id}", name="retrait_panier")
     */
    public function retraitPanier($id ,PanierService $panierService)
    {
        $panierService->remove($id);

        return $this->redirectToRoute('panier');

    }


    /**
     * @Route("/annulepanier/{id}", name="annule_panier")
     */
    public function annulePanier($id ,PanierService $panierService)
    {
        $panierService->delete($id);

        return $this->redirectToRoute('panier');

    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panier(PanierService $panierService, OeuvreRepository $oeuvreRepository)
    {
        $oeuvres = $oeuvreRepository->findAll();

        return $this->render("panier.html.twig",[
            'items' => $panierService->getFullPanier(),
            'total' => $panierService->getTotal(),
            'oeuvres'=>$oeuvres
        ]);
    }

    /**
     * @Route("/commande", name="commande")
     */
    public function commande(PanierService $panierService, EntityManagerInterface $manager)
    {

        /**
         * fonction appelant le service panier afin de le transformer en commande,
         * ainsi chaques articles avec leur quantit?? enregistr??s dans le panier correspondra ?? un achat.
         * le cumul de tout ces achats aura un seule et m??me id de commande et cr??era donc une commande reli??e par l'id aux achats, eux m??mes reli??s aux articles en bdd
         */

        $panier = $panierService->getFullPanier();


        $commande = new Commande();


        $commande->setTotal($panierService->getTotal());
        $commande->setUser($this->getUser());




        foreach ($panier as $item) {
            $oeuvre=$item['oeuvre'];
            $achat = new Achat();
            $achat->setOeuvre($item['oeuvre']);
            $achat->setQuantite(1);
            $achat->setPrix($item['oeuvre']->getPrix());
//            $article->setStock($article->getStock()-$item['quantite']);
            $manager->persist($achat);
            $manager->persist($oeuvre);
            $achat->setCommande($commande);
            $panierService->delete($item['oeuvre']->getId());

        }

        $commande->setDate(new \DateTime());
        $manager->persist($commande);
        $manager->flush();
        $this->addFlash('success', 'Commande valid??e');


        return $this->redirectToRoute('success');
    }

    /**
     * @Route("/success" , name="success")
     */
    public function SuccessCommandeUser()
    {
        return $this->render('success_commande_user.html.twig');
    }

    /**
     * @Route("/add", name="add_oeuvre")
     */
    public function add(Request $request, EntityManagerInterface $manager)
    {

        //ici nous allons cr??er un formulaire via le packager form de symfony, au pr??alable, nous avons
        // renseign?? ?? twig d'utiliser bootstrap4 dans config/package/twig.yaml, nous avons copi??       form_themes: ['bootstrap_4_layout.html.twig'] sous default_path

        //la classe Request (de component\HttpFondation) permet de v??hiculer les informations des superglobales ($_GET, $_POST, $_SESSION....)
        //$request est un objet issu de la classe REQUEST inject?? en d??pendance de la m??thode

        dump($request);

        $oeuvre= new Oeuvre();

        dump($oeuvre);


        // nous avons cr???? une classe qui permet de g??n??rer le formulaire d'ajout d'article, il faut dans le controller importer cette Type et relier le formulaire ?? notre instanciation d'entit?? article
        $form=$this->createForm(OeuvreType::class, $oeuvre, array('ajouter'=>true) );

        // on va chercher dans l'objet handlerequest qui permet de recuperer chaques donn??es saisie des champs de formulaire. il s'assure de la coordination entre formType et entity afin de g??n??rer les bon setteurs pour chaques propri??t?? de l'entit??
        $form->handleRequest($request);

        dump($oeuvre);
        dump($request);

        // ici on informe par la condition if. que si le bouton submit a ??t?? pr??ss?? et que les donn??es du formulaires sont conforme ?? notre entit?? (type) et ?? nos contrainte, il peut faire intervenir doctrine et son manager pour preparer puis executer les requ??tes
        if ($form->isSubmitted() && $form->isValid()):

            // on r??cup??re ici toutes les donn??es de l'input name="image"
            $imageFile=$form->get('image')->getData();

            // dd($imageFile);

            // ici on place le if pour v??rifier qu'une image a ??t?? upload?? dans notre input de formulaire, si oui, renverra true
            if ($imageFile):

                // on redefini le nom de notre image pour s'assur?? que celui ci soit unique et n'aille pas ??craser un autre fichier du m??me nom
                $nomImage=date("YmdHis")."-".uniqid()."-".$imageFile->getClientOriginalName();
                // dd($nomImage);

                // envoie de l'image dans images/imagesUpload

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $nomImage
                    );
                    // m??thode move () attend 2 param??tres et permet de d??placer un fichier des fichier temp du server vers un emplacement d??fini.
                    //   parametre1: l'emplacement d??fini, param??tr?? au pr??alable dans config/service.yaml    => images_directory: '%kernel.project_dir%/public/images/imagesUpload' ?? placer sous parameters.
                    // parametre2: le nom du fichier ?? deplacer

                }
                catch (FileException $e){

                    $this->redirectToRoute('add_oeuvre',[
                        'erreur'=> $e
                    ]);
                }
                // envoie du nouveau nom en BDD

                $oeuvre->setImage($nomImage);

            endif;

            $manager->persist($oeuvre);
            $manager->flush();

            // ici si tout s'est bien pass??, on donne redirection sur le catalogue
            $this->addFlash("success", "L'oeuvre a bien ??t?? ajout??e");

            return $this->redirectToRoute("gestion_oeuvres");

        endif;


        return $this->render(':admin/oeuvre:add_oeuvre.html.twig/',[
            'formOeuvre'=>$form->createView()
        ]);
    }

    /**
     * @Route("/gestioncommandes", name="gestion_commandes")
     */
    public function gestionCommandes(CommandeRepository $repository)
    {

        $commandes=$repository->findAll();
        return $this->render('admin/gestion_commandes.html.twig',[
            'commandes'=>$commandes
        ]);
    }


    /**
     * @Route("/mail", name="mail")
     */
    public function send_email(request $request)
    {

        if (!empty($request->request)):
            // dd($request->request->get('email'));
            $transporter = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                ->setUsername('elyes.asd7@gmail.com')
                ->setPassword('123456Asd');

            $mailer = new Swift_Mailer($transporter);
            $mess=$request->request->get('message');
            $nom=$request->request->get('surname');
            $prenom=$request->request->get('name');
            $motif=$request->request->get('need');

            $message = (new Swift_Message("$motif"))
                ->setFrom($request->request->get('email'))
                ->setTo(['elyes.asd7@gmail.com'=> 'Elyes']);
            $cid = $message->embed(Swift_Image::fromPath('fleche.png'));

            $message->setBody(

                $this->renderView('admin/Email/test.html.twig',[
                    'message'=>$mess,
                    'nom'=>$nom,
                    'prenom'=>$prenom,
                    'motif'=>$motif,
                    'email'=>$request->request->get('email'),
                    'cid'=>$cid
                ]),
                'text/html'
            );


// Send the message
            $result = $mailer->send($message);


            $this->addFlash('success', 'email envoy??');
            return $this->redirectToRoute('home');
        endif;
    }

    /**
     * @Route("/sendform", name="send_form")
     */
    public function form_email()
    {
        return $this->render('admin/Email/mail.html.twig');
    }

}
