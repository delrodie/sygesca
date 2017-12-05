<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StatistiquesController extends Controller
{

  /* =======================================================
   * ================== STATISTIQUES DES STATUTS ==========
   * =======================================================
  */

    /**
     * Nombre de cotisants par statut
     *
     * @Route("/statistiques/nombre-de-{statut}-cotisants", name="statistiques_nbstatut_contisant")
     */
    public function nbstatutcotisantsAction($statut)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoie de la valeur 0
        if ($cotisation === 0) {
          $nombre = 0;
        } else{
          $nombre = $em->getRepository('AppBundle:Scout')->getNombreStatutCotisant($statut, $cotisation->getAnnee());
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Pourcentage de cotisants par branche
     *
     * @Route("/statistiques/pourcentage-de-{statut}-cotisants", name="statistiques_pourcentagestatut_contisant")
     */
    public function pourcentagestatutcotisantsAction($statut)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoie la valeur 0
        if ($cotisation === 0) {
            $pourcentage = 0;
        } else {
            $cotisantTotal = $em->getRepository('AppBundle:Scout')->getNbTotCotisant($cotisation->getAnnee());

            if ($cotisantTotal === "0") {
              $cotisantTotal = 1;
            }

            $nombre = $em->getRepository('AppBundle:Scout')->getNombreStatutCotisant($statut, $cotisation->getAnnee());
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
     * @Route("/statistiques/nombre-de-{genre}-cotisantes", name="statistiques_nbgenre_contisant")
     */
    public function nbgenrecotisantesAction($genre)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoie de la valeur 0
        if ($cotisation === 0 ) {
          $nombre = 0;
        } else {
          $nombre = $em->getRepository('AppBundle:Scout')->getNombreGenreCotisant($genre, $cotisation->getAnnee());
        }


        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Pourcentage de cotisants par branche
     *
     * @Route("/statistiques/pourcentage-de-{genre}-cotisantes", name="statistiques_pourcentagegenre_contisant")
     */
    public function pourcentageGenrecotisantsAction($genre)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pa de cotisation alors renvoie de la valeur 0
        if ($cotisation === 0) {
           $pourcentage = 0;
        } else {
          $cotisantTotal = $em->getRepository('AppBundle:Scout')->getNbTotCotisant($cotisation->getAnnee());

          if ($cotisantTotal === "0") {
            $cotisantTotal = 1;
          }

          $nombre = $em->getRepository('AppBundle:Scout')->getNombreGenreCotisant($genre, $cotisation->getAnnee());
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
     * Nombre de cotisants par branche
     *
     * @Route("/statistiques/nombre-de-{statut}-{branche}-cotisants", name="statistiques_nbbranche_contisant")
     */
    public function nbBranchecotisantsAction($statut, $branche)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
            $nombre = 0;
        } else {
            $nombre = $em->getRepository('AppBundle:Scout')->getNombreBrancheCotisant($statut, $branche, $cotisation->getAnnee());
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Pourcentage de cotisants par branche
     *
     * @Route("/statistiques/pourcentage-de-{statut}-{branche}-cotisants", name="statistiques_pourcentagebranche_contisant")
     */
    public function pourcentageBranchecotisantsAction($statut, $branche)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors pourcentage est 0
        if ($cotisation === 0) {
            $pourcentage = 0;
        } else {
            $cotisantTotal = $em->getRepository('AppBundle:Scout')->getNbTotStatutCotisant($statut, $cotisation->getAnnee());

            if ($cotisantTotal === "0") {
              $cotisantTotal = 1;
            }

            $nbBranche = $em->getRepository('AppBundle:Scout')->getNombreBrancheCotisant($statut, $branche, $cotisation->getAnnee());
            $pourcentage = round($nbBranche*100/$cotisantTotal, 1);
        }


        return $this->render('statistiques/pourcentage.html.twig', array(
            'pourcentage' => $pourcentage,
        ));
    }

    /* =======================================================
     * ============== STATISTIQUES GLOBALES REGIONS ==========
     * =======================================================
    */

    /**
     * Liste des régions
     *
     * @Route("/statistiques/liste-des-regions", name="statistiques_region")
     */
    public function regionlisteAction()
    {
        $em = $this->getDoctrine()->getManager();

        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return $this->render('statistiques/region_liste.html.twig', array(
              'regions'  => $regions,
        ));
    }

    /**
     * Nombre total de cotisant par Region
     *
     * @Route("/statistiques/region{region}-cotisants-total", name="statistiques_region_total_cotisant")
     */
    public function regionTotalCotisantAction($region)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors la valeur 0
        if ($cotisation === 0) {
            $nombre = 0;
        } else {
            $annee = $cotisation->getAnnee();

            $nombre = $em->getRepository('AppBundle:Scout')->getNbTotalCotisantParregion($region, $annee);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Nombre total de cotisant par statut  de la Region
     *
     * @Route("/statistiques/region{region}-cotisants-{statut}", name="statistiques_region_statut_cotisant")
     */
    public function regionStatutCotisantAction($region, $statut)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
          $nombre = 0;
        } else {
          $annee = $cotisation->getAnnee();

          $nombre = $em->getRepository('AppBundle:Scout')->getNbStatutCotisantParregion($region, $statut, $annee);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Nombre total de cotisant par genre  de la Region
     *
     * @Route("/statistiques/region-{region}-cotisants-{genre}", name="statistiques_region_genre_cotisant")
     */
    public function regionGenreCotisantAction($region, $genre)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il n'y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
          $nombre = 0;
        } else {
          $annee = $cotisation->getAnnee();

          $nombre = $em->getRepository('AppBundle:Scout')->getNbGenreCotisantParregion($region, $genre, $annee);
        }

        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Statistiques adultes de la region
     *
     * @Route("/statistiques/adultes-listes-regions", name="statistiques_region_adultes")
     */
    public function regionAdultesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return $this->render('statistiques/region_adultes.html.twig', array(
              'regions'  => $regions,
        ));
    }

    /**
     * Nombre total de cotisant par statut  de la Region
     *
     * @Route("/statistiques/region/{region}-cotisants-{statut}-par-{branche}", name="statistiques_region_statut_branche")
     */
    public function regionStatutBrancheCotisantAction($region, $statut, $branche)
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        // S'il y a pas de cotisation alors renvoyer la valeur 0
        if ($cotisation === 0) {
           $nombre = 0;
        } else {
          $annee = $cotisation->getAnnee();

          $nombre = $em->getRepository('AppBundle:Scout')->getNbStatutBrancheCotisantParregion($region, $statut, $annee, $branche);
        }


        return $this->render('statistiques/nombres.html.twig', array(
            'nombre' => $nombre,
        ));
    }

    /**
     * Statistiques adultes de la region
     *
     * @Route("/statistiques/jeunes-listes-regions", name="statistiques_region_jeunes")
     */
    public function regionJeunesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return $this->render('statistiques/region_jeunes.html.twig', array(
              'regions'  => $regions,
        ));
    }

    /* =======================================================
     * ================== STATISTIQUES DES GLOBALES ==========
     * =======================================================
    */

    /**
     * Pourcentage des cotisants sur le nombre total enregistré
     *
     * @Route("/statistiques/globale", name="statistiques_globales")
     */
    public function globalAction()
    {
        $em = $this->getDoctrine()->getManager();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();

        if ($cotisation === 0) {
          $pourcentage = 0;
        } else {
          $enregistres = $em->getRepository('AppBundle:Scout')->getTotalScout();
          $cotisantTotal = $em->getRepository('AppBundle:Scout')->getNbTotCotisant($cotisation->getAnnee());

          if ($cotisantTotal === 0) {
            $cotisantTotal = 1;
          }

          if ($enregistres == 0) {
            $enregistres = 1;
          }

          $pourcentage = round($cotisantTotal*100/$enregistres, 1);
        }//dump($enregistres);die();


        return $this->render('statistiques/pourcentage.html.twig', array(
            'pourcentage' => $pourcentage,
        ));

    }

}
