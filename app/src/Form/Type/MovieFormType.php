<?php
declare(strict_types=1);


namespace App\Form\Type;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('duration', IntegerType::class)
            ->add('director', TextType::class)
            ->add('synopsis', TextType::class)
            ->add('score', IntegerType::class)
            ->add('visible', IntegerType::class, ['required' => false, 'empty_data' => '1',]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }


}