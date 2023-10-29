<?php

namespace App\form;
use App\Entity\Author;
use App\Entity\Books;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlayaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('ref')
            ->add('title')
            ->add('published')
            ->add('publicationdate' , TypeDateType::class, [
                'widget' => 'single_text', // Afficher en tant qu'entrée texte unique
                'format' => 'yyyy-MM-dd', // Définir le format de date souhaité
            ])

            ->add('category',ChoiceType::class,[
                    'choices'=>[
                        'Science-Fiction'=>'fgg' ,
                        'Mystery'=>'gg' ,
                        'Autobiography'=>'gg'

                    ]


                ]
            )


            ->add('author', EntityType::class, [
                'class' => Author::class, // Utilisez le nom complet de la classe Author
                'choice_label' => 'username', // Utilisez la propriété 'username' de l'entité Author
            ])
            ->add('submit', SubmitType::class);
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
        ]);

    }
}

