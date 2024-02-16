<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserRegistration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => 'pseudo', 'required' => true])
            ->add('password', PasswordType::class, ['label' => 'mot de passe', 'required' => true])
            ->add('submit', SubmitType::class, ['label' => 'valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translations_domain' => 'forms.fr.yaml'
        ]);
    }
}
