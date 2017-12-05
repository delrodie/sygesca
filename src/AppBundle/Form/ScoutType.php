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

class ScoutType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];

        $user = $this->user;

        $builder
            //->add('matricule')
            ->add('nom', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'Nom de famille'
                  )
            ))
            ->add('prenoms', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      //'placeholder' => ''
                  )
            ))
            ->add('datenaiss', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'dd-mm-yyyy',
                       'data-mask'  => "99-99-9999",
                  )
            ))
            ->add('lieunaiss', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      //'placeholder' => 'Sans la mention DISTRICT'
                  )
            ))
            ->add('sexe', ChoiceType::class, array(
                  'choices' => array(
                    'Masculin'  => 'M',
                    'Feminin '  => 'F',
                  ),
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      //'placeholder' => 'Sans la mention DISTRICT'
                  )
            ))
            ->add('nationalite', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      //'placeholder' => 'Sans la mention DISTRICT'
                  ),
                  'required' => false,
            ))
            ->add('contact', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'Contact téléphonique'
                  ),
                  'required' => false,
            ))
            ->add('contactparent', TextType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'Telephone du père ou de la mère ou du tuteur legal'
                  ),
                  'required' => false,
            ))
            ->add('email', EmailType::class, array(
                  'attr'  => array(
                      'class' => 'form-control',
                      'autocomplete'  => 'off',
                      'placeholder' => 'Adresse Email'
                  ),
                  'required' => false,
            ))
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('statut', null, array(
                  'attr'  => array(
                      'class' => 'form-control selectpicker',
                      'data-live-search'  => 'true',
                      'onChange' => 'statut()'
                      //'placeholder' => 'Numéro de téléphone'
                  )
            ))
            ->add('groupe', EntityType::class, array(
                'attr'  => array(
                    'class' => 'form-control selectpicker',
                    'data-live-search'  => 'true',
                    'autocomplete'  => 'off'
                ),
                'required' => true,
                'class' => 'AppBundle:Groupe',
                'query_builder' =>  function(EntityRepository $er) use($user){
                        return $er->getGroupeByRegion($user);
                      },
                      'choice_label'  => 'paroisse',
                )
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Scout',
            'user'  => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_scout';
    }


}
