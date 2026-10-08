<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;

/**
 * Generated the form on Twig for the Product.
 */
class ProductType extends AbstractType
{
    // Creates the form
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('size', options: [
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('is_available')

            /**
             * This is special, because this has the relations with the Category entity.
             */
            ->add('categories', EntityType::class, [
                // categories belong to Category entity
                'class' => Category::class,
                // display the category name as the choice label
                'choice_label' => 'name',
                // allow multiple selections and display as checkboxes
                'multiple' => true,
                // display as checkboxes instead of a select dropdown
                'expanded' => true,
                // ensure that the categories are not modified by reference
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Product',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}