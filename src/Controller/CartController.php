<?php

namespace App\Controller;

use App\Service\CartService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cs): Response
    {
        
        

        return $this->render('cart/index.html.twig', [
            'items' => $cs->getCartWithData(),
            'total' => $cs->getTotal()
        ]);
    }
    /**
    * @Route("/cart/add/{id}", name="cart_add")
    */
    public function add($id, CartService $cs)
    {
        $cs->add($id);
        // je sauvegarde l'etat de mpon panier en session a l'attr de session "cart"
        return $this->redirectToRoute('app_cart');
        //dd() = dump & die : afficher des infos et tuer l'execution du code
    }

    /**
    * @Route("/cart/lessAction/{id}", name="cart_lessAction")
    */
    public function lessAction($id, CartService $cs)
    {
        $cs->lessAction($id);
        // je sauvegarde l'etat de mpon panier en session a l'attr de session "cart"
        return $this->redirectToRoute('app_cart');
        //dd() = dump & die : afficher des infos et tuer l'execution du code
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cs)
    {
        $cs->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/remove", name="cart_deleteAll")
     */
    public function deleteAll(CartService $cs)
    {
        $cs->deleteAll();
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/payment", name="cart_payment")
     */
    public function payment(CartService $cs)
    {
        $cs->deleteAll();
        return $this->render('cart/payment.html.twig');
    }
}
