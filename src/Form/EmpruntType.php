<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('livre', EntityType::class, [ "class" => Livre::class, "choice_label" => "titre" ] )
            ->add('abonne', EntityType::class, [ "class" => Abonne::class, "choice_label" => "prenom" ] )
            ->add('date_sortie', DateType::class, [ "widget" => "single_text" ])
            ->add('date_rendu', DateType::class, [ "widget" => "single_text", "required" => false ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
