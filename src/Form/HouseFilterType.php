<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\HouseFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class HouseFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('max_price', IntegerType::class, ['required' => false, 'label' => false, 'attr' => ['placeholder' => 'prix maximum']])
            ->add('min_price', IntegerType::class, ['required' => false, 'label' => false, 'attr' => ['placeholder' => 'prix minimum']])
            ->add('min_rooms', IntegerType::class, ['required' => false, 'label' => false, 'attr' => ['placeholder' => 'nombre de pièces minimum']])
            ->add('min_surface', IntegerType::class, ['required' => false, 'label' => false, 'attr' => ['placeholder' => 'surface minimum']])

            ->add('options', EntityType::class, [
                'class' => Option::class, 
                'choice_label' => 'name', 
                'multiple' => true,
                'required' => false,
                'label' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HouseFilter::class,
            'method' => 'get',      //pour persister les critères de filtrage en cas de changement de page
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
