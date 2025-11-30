<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('marque')
            ->add('modele')
            ->add('image_url')
            ->add('km')
            ->add('slug')
            ->add('prix')
            ->add('nb_proprio')
            ->add('cylindre')
            ->add('puissance')
            ->add('carburant')
            ->add('date_circu')
            ->add('transmission')
            ->add('description')
            ->add('option_txt')
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true, // permet d'ajouter des éléments surtout d'avoir attribut html data_prototype
                'allow_delete' => true // permet de supprimer une entrée
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
