<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class,[
                'label' => 'Login',
                'required' => true,
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent etre identiques',
                'required' => true,
                'first_name' => 'pass',
                'second_name' => 'confirm',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('surname',TextType::class,[
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('dateOfBirth', DateType::class,[
                'label' => 'Date de naissance',
                'html5' => false,
                'attr' => ['class' => 'flatpickr'],
                'format' => 'dd-MM-yyyy',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
