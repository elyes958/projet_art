<?php

namespace App\Form;

use App\Entity\Oeuvre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('image', FileType::class, [
                "data_class" => null,
                "required" => false,
                'constraints' => [ 
                    new File([
                        'mimeTypes' => [
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                        ],
                        'mimeTypesMessage' => "Extensions Autorisées : PNG JPG JPEG"
                    ])
                ]
            ])
            ->add('description')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
        ]);
    }
}
