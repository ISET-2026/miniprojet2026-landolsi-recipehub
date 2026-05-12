<?php

namespace App\Form;

use App\Entity\CategorieRecette;
use App\Entity\TagRecette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'class'       => 'form-control',
                    'placeholder' => '🔍 Rechercher par titre...',
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class'        => CategorieRecette::class,
                'choice_label' => 'nom',
                'label'        => false,
                'required'     => false,
                'placeholder'  => '📂 Toutes les catégories',
                'attr'         => ['class' => 'form-control'],
            ])
            ->add('difficulte', ChoiceType::class, [
                'label'    => false,
                'required' => false,
                'choices'  => [
                    'Toutes les difficultés' => null,
                    '🟢 Facile'              => 'facile',
                    '🟡 Moyen'               => 'moyen',
                    '🔴 Difficile'           => 'difficile',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tag', EntityType::class, [
                'class'        => TagRecette::class,
                'choice_label' => 'nom',
                'label'        => false,
                'required'     => false,
                'placeholder'  => '🏷️ Tous les tags',
                'attr'         => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}

