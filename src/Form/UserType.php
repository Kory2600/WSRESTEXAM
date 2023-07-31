<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isCreation = $options['is_creation'];

        $builder
            ->add('password',  PasswordType::class, [
                'label' => 'form.user.password.label',
            ])
            ->add('name', null, [
                'label' => 'form.user.name.label',
            ]);
        
        if ($isCreation) {
            $builder
            ->add('email', EmailType::class, [
                'label' => 'form.user.email.label',
            ]);
        }


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_creation' => true,
        ]);
    }
}
