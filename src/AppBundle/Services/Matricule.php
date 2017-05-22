<?php

namespace AppBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generation du Matricule du scout
 *
 * @author: Delrodie AMOIKON
 * @version: v1.0 18/05/2017 08:09
 */
class Matricule
{

  function __construct(ContainerInterface $container, $entityManager)
  {
      $this->container = $container;
      $this->em = $entityManager;
  }

  /**
   * Generation du matricule
   *
   * @param string $groupe
   *
   * @return $matricule
   */
  public function matricule($groupe)
  {
      // Recueration du code de la région
      $region = $this->em->getRepository('AppBundle:Region')->getRegionCode($groupe);

      // Generation du matricule du scout
      $code = $this->em->getRepository('AppBundle:Scout')->generationCode();

      // Generation d'une lettre aleatoire
      $lettre = $this->em->getRepository('AppBundle:Scout')->generationLettre();

      $matricule = $region.''.$code.''.$lettre;

      return $matricule;
  }
}
