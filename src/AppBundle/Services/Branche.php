<?php

namespace AppBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generation du Matricule du scout
 *
 * @author: Delrodie AMOIKON
 * @version: v1.0 26/05/2017 18:34
 */
class Branche
{

  function __construct(ContainerInterface $container)
  {
      $this->container = $container;
  }

  /**
   * Affection de la branche
   *
   * @param string $datenaiss
   *
   * @return $branche
   */
  public function branche($datenaiss)
  {
      $date = new \DateTime();

      $annee1 = substr($date->format('d-m-Y'), -4, 4);
      $annee2 = substr($datenaiss, -4, 4);

      $age = $annee1 - $annee2;

      if ($age < 12) {
        $branche = 'Meute';
      } elseif ($age >= 12 && $age < 15) {
        $branche = "Troupe";
      } elseif ($age >= 15 && $age < 18) {
        $branche = "Generation";
      } else{
        $branche = "Communaute";
      }

      return $branche;
  }
}
