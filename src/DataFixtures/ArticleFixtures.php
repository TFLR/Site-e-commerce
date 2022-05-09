<?php

namespace App\DataFixtures;
use App\Entity\Produit;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       for($i = 1; $i <=10; $i++){
           $produit = new Produit;

           $produit->setNom("Titre du produit n°$i")
                    ->setDescription("<p>Contenu de l'article n°$i</p>")
                    ->setPrix(random_int(1,1000))
                    ->setImage("<p>Contenu de l'article n°$i</p>")
                    ->setCategorieId(null)
                    ->setUserId(null);

                    $manager->persist($produit);
                }

        $manager->flush();
    }
}
