<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class,[
                'label' => 'Votre Login :',
                'required' => true,
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent etre identiques',
                'required' => true,
                'first_name' => 'pass',
                'second_name' => 'confirm',
                'first_options' => ['label' => 'Votre mot de passe :'],
                'second_options' => ['label' => 'Confirmez votre mot de passe :'],
            ])
            ->add('surname')
            ->add('firstname')
            ->add('dateOfBirth')
            ->add('send', SubmitType::class,[
                'label' => 'Valider l\'inscription',
            ])
            //->add('isAdmin')
            //->add('isSuperAdmin')
            //->add('shoppingBasket')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
