<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File;

class FormLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // mettre des contraintes selon la longueur de type de données 
        // (auteur varchar20, titre varchar 50)
        // ajouter un bouton submit
        $builder
            ->add('auteur', TextType::class, [ "constraints" => 
                                                    [ new Length([ "max" => 25,
                                                                   "maxMessage" => "Le nom de l'auteur ne peut pas dépasser {{ limit }} caractères",
                                                                   "help" => "Le nom de l'auteur ne peut pas dépasser 25 caractères"
                                                                 ]) 
                                                    ] 
                                                 ])
            ->add('titre', TextType::class, [ "constraints" => 
                                                [ new Length([ "max" => 50,
                                                               "maxMessage" => "Le titre ne peut pas dépasser 50 caractères"
                                                             ]) 
                                                ] 
                                            ])
            
            ->add('couverture', FileType::class, [ "mapped" => false, "required" => false,
                    "constraints" => [ new File([ "mimeTypes" => [ "image/gif", "image/jpeg", "image/png" ],
                                                    "mimeTypesMessage" => "Les formats autorisés sont gig, jpeg, png",
                                                    "maxSize" => "2048k",
                                                    "maxSizeMessage" => "Le fichier ne peut pas faire plus de 2Mo"
                                                ])]
                ])
            
            ->add('enregistrer', SubmitType::class, [ 'attr' => [
                                                            'class' => 'btn btn-primary'
                                                          ]
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
