<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $repo;
    private $rs;

    public function __construct(ProduitRepository $repo, RequestStack $rs)
    {
        $this->repo = $repo;
        $this->rs = $rs;
    }

    public function add($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) 
            $cart[$id]++;
        else
            $cart[$id] =1;

        $session->set('cart', $cart);
    }

    public function lessAction($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) 
            if ($cart[$id]>1) {
                $cart[$id]--;
            }
            else {
                unset($cart[$id]);
            }
        else
            $cart[$id] =1;

        $session->set('cart', $cart);
    }

    public function remove($id)
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart',[]);

        if (!empty($cart[$id])) 
            unset($cart[$id]);
            
        $session->set('cart',$cart);
    }

    public function deleteAll()
    {
        $session = $this->rs->getSession();
        $clear = [];
        
            
        $session->set('cart',$clear);
        
    }

    public function getCartWithData()
    {
        $session = $this->rs->getSession();
        $cart = $session->get('cart', []);
        $cartWithData = [];
        $qt = 0;

        foreach ($cart as $id =>$quantite) {
            $cartWithData[] = [
                'produit' => $this->repo->find($id), 
                'quantite' => $quantite
            ];
            $qt += $quantite;
        }
        $session->set('qt', $qt);
        return $cartWithData;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getCartWithData() as $item) {
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }
        return $total;
    }
}