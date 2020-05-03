<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("image", FileType::class, [ 
                "mapped" => false,
                "required" => false,
                "label" => "Couverture"
            ])
            ->add('auteur')
            ->add('titre')
            ->add('enregistrer', SubmitType::class, [ 
                "label" => "Enregistrer",
                "attr" => [
                    "class" => "btn btn-primary"
                ],
                "row_attr" => [ "class" => "float-left" ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
