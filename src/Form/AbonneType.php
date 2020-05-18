<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', Type\TextType::class, [
                "label" => "Identifiant (prénom)"
            ])
            ->add('roles', Type\ChoiceType::class, [ 
                "choices" => [
                    "Lecteur" => "ROLE_USER", 
                    "Bibliothécaire" => "ROLE_BIBLIOTHECAIRE",
                    "Directeur" => "ROLE_ADMIN",
                    "Dév" => "ROLE_DEV"
                ],
                "multiple" => true,
                "label" => "Statut",
                "expanded" => true,
                // "mapped" => false,
                "attr" => [ 
                    "class" => "unique"
                ]
            ])
            ->add('password', Type\PasswordType::class, [ 
                "mapped" => false,
                    "constraints" => [
                        new Constraints\Length([ "min" => 6,
                                                    "minMessage" => "Le mot de passe doit contenir au moins {{ limit }} caractères"
                        ])
                    ]
            ])
            ->add('enregistrer', Type\SubmitType::class, [ 
                "label" => "Enregistrer",
                "attr" => [
                    "class" => "btn btn-primary"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
