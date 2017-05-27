<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\QueryBuilder;

class DistrictType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];

        $user = $this->user;

        $builder
            ->add('nom', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'Sans la mention DISTRICT'
                  )
            ))
            ->add('doyenne', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'Doyenne ou le SECTEUR du district'
                  ),
                  'required' => false
            ))
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('region', EntityType::class, array(
                'attr'  => array(
                    'class' => 'form-control select-region',
                    'autocomplete'  => 'off'
                ),
                'class' => 'AppBundle:Region',
                'query_builder' =>  function(EntityRepository $er) use($user){
                        return $er->getRegionNom($user);
                  },
                  'choice_label'  => 'nom',
            ))
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\District',
            'user'      => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_district';
    }


}
