<?php

namespace App\Form;

use App\Entity\House;
use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class HouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'titre'])
            ->add('description', null, ['label' => 'description'])
            ->add('surface', null, ['label' => 'surface'])
            ->add('rooms', null, ['label' => 'pièces'])
            ->add('bedrooms', null, ['label' => 'chambres'])
            ->add('floor', null, ['label' => 'étage'])
            ->add('price', null, ['label' => 'prix'])
            ->add('heat', ChoiceType::class, ['choices' => $this->getChoices(), 'label' => 'chauffage'])
            
            ->add('options', EntityType::class, [
                'class' => Option::class, 
                'choice_label' => 'name', 
                'multiple' => true,
                'required' => false,
                'label' => 'Choisissez une ou plusieurs options pour ce bien'
                ])

            ->add('city', null, ['label' => 'ville'])
            ->add('address', null, ['label' => 'adresse'])
            ->add('postal_code', null, ['label' => 'code postal'])
            ->add('sold', null, ['label' => 'vendu'])

            ->add('imageFile', VichImageType::class, ['label' => 'photo à la une', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => House::class,
            'translations_domain' => 'forms.fr.yaml'
        ]);
    }

    private function getChoices()
    {
        $choices = [];
        foreach(House::HEAT as $value => $label)
        {
            $choices[$label] = $value;
        }
        return $choices;
    }
}
