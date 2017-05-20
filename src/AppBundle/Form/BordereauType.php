<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\QueryBuilder;

class BordereauType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->cotisation = $options['cotisation'];

        $cotisation = $this->cotisation;

        $builder
            //->add('numero')
            ->add('montant', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      //'placeholder' => 'Sans la mention DISTRICT'
                  )
            ))
            ->add('valide')
            //->add('cotisants')->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('cotisation', EntityType::class, array(
                'attr'  => array(
                    'class' => 'form-control',
                    'autocomplete'  => 'off'
                ),
                'class' => 'AppBundle:Cotisation',
                'query_builder' =>  function(EntityRepository $er) use($cotisation){
                        return $er->getCotisationAnnee($cotisation);
                  },
                  'choice_label'  => 'annee',
            ))
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Bordereau',
            'cotisation'=> null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_bordereau';
    }


}
