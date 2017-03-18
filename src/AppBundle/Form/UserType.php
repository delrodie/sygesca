<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', TextType::class, array(
              'attr'  => array(
                  'class' => 'form-control',
                  'autocomplete'  => 'off'
              )
        ))
        //->add('usernameCanonical')
        ->add('email', EmailType::class, array(
              'attr'  => array(
                  'class' => 'form-control',
                  'autocomplete'  => 'off'
              )
        ))
        //->add('emailCanonical')
        ->add('enabled')
        //->add('salt')
        ->add('password', PasswordType::class, array(
              'attr'  => array(
                  'class' => 'form-control'
              )
        ))
        //->add('lastLogin')
        //->add('locked')
        //->add('expired')
        //->add('expiresAt')
        //->add('confirmationToken')
        //->add('passwordRequestedAt')
        ->add('roles', ChoiceType::class, array(
              'choices' => array(
                'UTILISATEUR '  => 'ROLE_USER',
                'ADMINISTRATEUR '  => 'ROLE_ADMIN',
                'SUPER ADMINISTRATEUR '  => 'ROLE_SUPER_ADMIN',
              ),
              'multiple'  => true,
              'expanded'  => true
        ))
        //->add('credentialsExpired')
        //->add('credentialsExpireAt')
        //->add('loginCount')
        //->add('firstLogin')
        //->add('groups')

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
