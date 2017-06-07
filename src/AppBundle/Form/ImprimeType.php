<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\QueryBuilder;

class ImprimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sexe', ChoiceType::class, array(
                  'choices' => array(
                    '-- Choisissez le genre --' => NULL,
                    'Masculin'  => 'M',
                    'Feminin '  => 'F',
                  ),
                  'attr'  => array(
                      'class' => 'form-control selectpicker',
                      'data-live-search'  => 'true',
                  )
            ))
            ->add('statut', ChoiceType::class, array(
                  'choices' => array(
                    '-- Choisissez le statut --' => NULL,
                    'Adulte'  => 'Adulte',
                    'Jeune '  => 'Jeune',
                  ),
                  'attr'  => array(
                      'class' => 'form-control selectpicker',
                      'data-live-search'  => 'true',
                  )
            ))
            ->add('branche', ChoiceType::class, array(
                  'choices' => array(
                    '-- Choisissez la branche --' => NULL,
                    'Meute'  => 'Meute',
                    'Troupe '  => 'Troupe',
                    'Generation '  => 'Generation',
                    'Communaute '  => 'Communaute',
                  ),
                  'attr'  => array(
                      'class' => 'form-control selectpicker',
                      'data-live-search'  => 'true',
                  )
            ))
            ->add('fonction', ChoiceType::class, array(
                  'choices' => array(
                    '-- Choisissez la branche --' => NULL,
                    'Louveteau'  => 'Louveteau',
                    'Eclaireur'  => 'Eclaireur',
                    'Cheminot'  => 'Cheminot',
                    'Routier'  => 'Routier',
                    'Chef d\'unité'  => 'CU',
                    'Chef de groupe'  => 'CG',
                    'Equipe de district'  => 'ED',
                    'CD'  => 'CD',
                    'Regional'  => 'Regional',
                    'Equipe Régionale'  => 'ER',
                    'Aumonier'  => 'Aumonier',
                    'Ainé'  => 'Aine',
                    'CND'  => 'CND',
                    'Commissaire National'  => 'CN',
                    'Equipe Nationale'  => 'EN',
                  ),
                  'attr'  => array(
                      'class' => 'form-control selectpicker',
                      'data-live-search'  => 'true',
                  )
            ))
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Scout',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'imprime_search';
    }


}
