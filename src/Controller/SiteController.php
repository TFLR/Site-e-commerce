<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\ContactType;
use App\Form\ProductType;
use App\Form\RechercheType;
use App\Form\CommentaireType;
use App\Notification\ContactNotification;
use App\Repository\ArticleRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ProduitRepository $repo, Request $request): Response
    {
        $form = $this->createForm(RechercheType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())    // si on fait une recherche
        {
            $data = $form->get('recherche')->getData(); // je récupère la saisie de l'utilisateur
            $produits = $repo->getProduitsByName($data);
        }
        else    // pas de recherche : on récupère tous les articles
        {
            $produits = $repo->findBy([], ["nom" => "ASC"]);
        }

        return $this->render('site/index.html.twig',[
            'produits' => $produits,
            'formRecherche' => $form->createView()
        ]);
        // pour envoyer des variables à une vue, on les passe dans un tableau associatif
        // indice => valeur
    }

    /**
     * @Route("/show/{id}", name="show_product")
     */
    public function show(Request $request, EntityManagerInterface $manager,Produit $produit,Commentaire $commentaire=null)
    {
        $user = $this->getUser();
        $commentaire = new Commentaire;
        $commentaire->setUserId($user);
        $commentaire->setProduitId($produit);
        $commentaire->setCreatedAt(new \DateTime());

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        dump($form);


        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($commentaire);
            $manager->flush();
            return $this->redirectToRoute('show_product',[
                'id' => $produit->getId()
            ]
            );
        }

        return $this->render('site/show.html.twig',[
            'produit'=> $produit,
            'formCommentaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil/{id}", name="user_profil")
     */
    public function showUserProfil(User $user)
    {
        return $this->render('site/userprofil.html.twig',[
            'user'=> $user
        ]);
    }


    /**
     * @Route("/profil", name="show_profil")
     */
    public function showProfil(ProduitRepository $repo)
    {
        $produits = $repo->findAll();
        return $this->render('site/profil.html.twig',[
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/new", name="new_product")
     * @Route("/edit/{id}", name="edit_product")
     */
    public function form(Request $request, EntityManagerInterface $manager, Produit $produit=null)
    {
        if(!$produit){
            $user = $this->getUser();
            $produit = new Produit;
            $produit->setUserId($user);
        }
    
        $form = $this->createForm(ProductType::class, $produit);
        $form->handleRequest($request);
        dump($form);
    
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($produit);
            $manager->flush();
            return $this->redirectToRoute('show_product',[
                'id' => $produit->getId()
            ]);
        }
        return $this->render('site/form.html.twig',[
            'editMode' => $produit->getId() !== null,
            'formProduit' => $form->createView()
        ]);
    }

}
    