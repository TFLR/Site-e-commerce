<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType as TypeEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prix')
            ->add('image')
            ->add('categorie_id', EntityType::class, [  // j'indique que le champ category est une entity
                'class' => Categorie::class, // je précise quelle entity
                'choice_label' => 'titre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate'
            ],
            'data_class' => Produit::class,
        ]);
    }
}
