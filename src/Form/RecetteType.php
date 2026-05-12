<?php

namespace App\Form;

use App\Entity\CategorieRecette;
use App\Entity\Recette;
use App\Entity\TagRecette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la recette',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: Tarte aux pommes...'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 4],
            ])
            ->add('instructions', TextareaType::class, [
                'label' => 'Instructions',
                'attr' => ['class' => 'form-control', 'rows' => 8],
            ])
            ->add('tempsPreparation', IntegerType::class, [
                'label' => 'Temps de préparation (minutes)',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tempsCuisson', IntegerType::class, [
                'label' => 'Temps de cuisson (minutes)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('difficulte', ChoiceType::class, [
                'label' => 'Difficulté',
                'choices' => [
                    'Facile' => 'facile',
                    'Moyen' => 'moyen',
                    'Difficile' => 'difficile',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nbPersonnes', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('publiee', CheckboxType::class, [
                'label' => 'Publier cette recette ?',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('categorie', EntityType::class, [
                'class' => CategorieRecette::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tags', EntityType::class, [
                'class' => TagRecette::class,
                'choice_label' => 'nom',
                'label' => 'Tags',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPEG, PNG, WebP)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Veuillez uploader une image JPEG, PNG ou WebP.',
                    ]),
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => '💾 Enregistrer',
                'attr' => ['class' => 'btn btn-primary w-100 mt-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}