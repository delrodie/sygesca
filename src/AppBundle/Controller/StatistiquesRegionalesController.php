<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StatistiquesRegionalesController extends Controller
{

  /* =======================================================
   * ================== STATISTIQUES DES STATUTS ==========
   * =======================================================
  */

    /**
     * Nombre de cotisants par statut
     *
     * @Route("/statistiques-regionales/nombre-de-{statut}-cotisants-{region}", name="statistiques_nbstatut_contisant_regional")
     */
    public function nbstatutcotisantsAction($statut, $region )
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoie de la valeur 0
        if ($cotisation === 0) {
          $nombre = 0;
        } else{
          $nombre = $em->getRepository('AppBundle:Scout')->getNombreStatutCotisantRegional($statut, $cotisation->getAnnee(), $region);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Pourcentage de cotisants par branche
     *
     * @Route("/statistiques-regionales/pourcentage-de-{statut}-cotisants-{region}", name="statistiques_pourcentagestatut_contisant_regional")
     */
    public function pourcentagestatutcotisantsAction($statut, $region)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoie la valeur 0
        if ($cotisation === 0) {
            $pourcentage = 0;
        } else {
            $cotisantTotal = $em->getRepository('AppBundle:Scout')->getNbTotCotisantRegional($cotisation->getAnnee(), $region);

            if ($cotisantTotal === "0") {
              $cotisantTotal = 1;
            }

            $nombre = $em->getRepository('AppBundle:Scout')->getNombreStatutCotisantRegional($statut, $cotisation->getAnnee(), $region);
            $pourcentage = round($nombre*100/$cotisantTotal, 1);
        }


        return $this->render('statistiques/pourcentage.html.twig', array(
            'pourcentage' => $pourcentage,
        ));
    }


    /* =======================================================
     * ================== STATISTIQUES DES GENRES ==========
     * =======================================================
    */

    /**
     * Nombre de cotisants par genre
     *
     * @Route("/statistiques-region/nombre-de-{genre}-cotisantes-{region}", name="statistiques_nbgenre_contisant_regional")
     */
    public function nbgenrecotisantesAction($genre, $region)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoie de la valeur 0
        if ($cotisation === 0 ) {
          $nombre = 0;
        } else {
          $nombre = $em->getRepository('AppBundle:Scout')->getNombreGenreCotisantRegional($genre, $cotisation->getAnnee(), $region);
        }


        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Pourcentage de cotisants par branche
     *
     * @Route("/statistiques-regionales/pourcentage-de-{genre}-cotisantes-{region}", name="statistiques_pourcentagegenre_contisant_regional")
     */
    public function pourcentageGenrecotisantsAction($genre, $region)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pa de cotisation alors renvoie de la valeur 0
        if ($cotisation === 0) {
           $pourcentage = 0;
        } else {
          $cotisantTotal = $em->getRepository('AppBundle:Scout')->getNbTotCotisantRegional($cotisation->getAnnee(), $region);

          if ($cotisantTotal === "0") {
            $cotisantTotal = 1;
          }

          $nombre = $em->getRepository('AppBundle:Scout')->getNombreGenreCotisantRegional($genre, $cotisation->getAnnee(), $region);
          $pourcentage = round($nombre*100/$cotisantTotal, 1);
        }


        return $this->render('statistiques/pourcentage.html.twig', array(
            'pourcentage' => $pourcentage,
        ));
    }

    /* =======================================================
     * ================== STATISTIQUES DES BRANCHES ==========
     * =======================================================
    */

    /**
     * Nombre de cotisants par branche de la region
     *
     * @Route("/statistiques-regionales/nombre-de-{statut}-{branche}-cotisants-{region}", name="statistiques_nbbranche_contisant_regional")
     */
    public function nbBranchecotisantsAction($statut, $branche, $region)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
            $nombre = 0;
        } else {
            $nombre = $em->getRepository('AppBundle:Scout')->getNombreBrancheCotisantRegional($statut, $branche, $cotisation->getAnnee(), $region);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Pourcentage de cotisants par branche
     *
     * @Route("/statistiques-regionales/pourcentage-de-{statut}-{branche}-cotisants-{region}", name="statistiques_pourcentagebranche_contisant_regional")
     */
    public function pourcentageBranchecotisantsAction($statut, $branche, $region)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors pourcentage est 0
        if ($cotisation === 0) {
            $pourcentage = 0;
        } else {
            $cotisantTotal = $em->getRepository('AppBundle:Scout')->getNbTotStatutCotisantRegional($statut, $cotisation->getAnnee(), $region);

            if ($cotisantTotal === "0") {
              $cotisantTotal = 1;
            }

            $nbBranche = $em->getRepository('AppBundle:Scout')->getNombreBrancheCotisantRegional($statut, $branche, $cotisation->getAnnee(), $region);
            $pourcentage = round($nbBranche*100/$cotisantTotal, 1);
        }

        return $this->render('statistiques/pourcentage.html.twig', array(
            'pourcentage' => $pourcentage,
        ));
    }

    /**
     * Nombre total de cotisant par District
     *
     * @Route("/statistiques-regionales/district{district}-cotisants-total", name="statistiques_district_total_cotisant")
     */
    public function districtTotalCotisantAction($district)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors la valeur 0
        if ($cotisation === 0) {
            $nombre = 0;
        } else {
            $annee = $cotisation->getAnnee();

            $nombre = $em->getRepository('AppBundle:Scout')->getNbTotalCotisantParDistrict($district, $annee);
        }

        return $this->render('statistiques/histogramme_region.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /* =======================================================
     * ============== STATISTIQUES GLOBALES DISTRICTS ==========
     * =======================================================
    */

    /**
     * Liste des districts
     *
     * @Route("/statistiques-regionales/liste-des-districts-{region}", name="statistiques_districts")
     */
    public function districtlisteAction($region)
    {
        $em = $this->getDoctrine()->getManager();

        $districts = $em->getRepository('AppBundle:District')->findBy(array('region'  => $region), array('nom'  => 'ASC'));

        return $this->render('statistiques/district_liste.html.twig', array(
              'districts'  => $districts,
        ));
    }

    /**
     * Nombre total de cotisant par statut  du district
     *
     * @Route("/statistiques-regionales/districts{district}-cotisants-{statut}", name="statistiques_district_statut_cotisant")
     */
    public function districtStatutCotisantAction($district, $statut)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
          $nombre = 0;
        } else {
          $annee = $cotisation->getAnnee();

          $nombre = $em->getRepository('AppBundle:Scout')->getNbStatutCotisantParDistrict($district, $statut, $annee);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Nombre total de cotisant par genre  du district
     *
     * @Route("/statistiques-regionales/district-{district}-cotisants-{genre}", name="statistiques_district_genre_cotisant")
     */
    public function regionGenreCotisantAction($district, $genre)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
          $nombre = 0;
        } else {
          $annee = $cotisation->getAnnee();

          $nombre = $em->getRepository('AppBundle:Scout')->getNbGenreCotisantParDistrict($district, $genre, $annee);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Statistiques adultes de la region
     *
     * @Route("/statistiques-regionales/adultes-listes-districts-{region}", name="statistiques_district_adultes")
     */
    public function districtAdultesAction($region)
    {
        $em = $this->getDoctrine()->getManager();

        $districts = $em->getRepository('AppBundle:District')->findBy(array('region' => $region), array('nom' => 'ASC'));

        return $this->render('statistiques/district_adultes.html.twig', array(
              'districts'  => $districts,
        ));
    }

    /**
     * Nombre total de cotisant par statut  de la Region
     *
     * @Route("/statistiques-regionales/district/{district}-cotisants-{statut}-par-{branche}", name="statistiques_district_statut_branche")
     */
    public function districtStatutBrancheCotisantAction($district, $statut, $branche)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
           $nombre = 0;
        } else {
          $annee = $cotisation->getAnnee();

          $nombre = $em->getRepository('AppBundle:Scout')->getNbStatutBrancheCotisantParDistrict($district, $statut, $annee, $branche);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Statistiques jeunes du district
     *
     * @Route("/statistiques-regionales/jeunes-listes-district-{region}", name="statistiques_district_jeunes")
     */
    public function districtJeunesAction($region)
    {
        $em = $this->getDoctrine()->getManager();

        $districts = $em->getRepository('AppBundle:District')->findBy(array('region' => $region), array('nom' => 'ASC'));

        return $this->render('statistiques/district_jeunes.html.twig', array(
              'districts'  => $districts,
        ));
    }

}
