<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\QueryBuilder;

class GroupeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->district = $options['district'];

        $district = $this->district;

        $builder
            ->add('paroisse', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'Sans la mention PAROISSE'
                  )
            ))
            ->add('localite', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'La ville, commune, village, ou le quartier de la paroisse '
                  )
            ))
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('district', EntityType::class, array(
                'attr'  => array(
                    'class' => 'form-control',
                    'autocomplete'  => 'off'
                ),
                'class' => 'AppBundle:District',
                'query_builder' =>  function(EntityRepository $er) use($district){
                        return $er->getDistrictNom($district);
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
            'data_class' => 'AppBundle\Entity\Groupe',
            'district'      => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_groupe';
    }


}
