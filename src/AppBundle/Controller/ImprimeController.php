<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Scout;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Imprime controller.
 *
 * @Route("imprime")
 */
class ImprimeController extends Controller
{
    /**
     * Liste des scouts de la région.
     *
     * @Route("/liste-des-scouts-de-votre-region", name="imprime_region_liste")
     * @Method({"GET", "POST"})
     */
    public function regionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();
        if ($cotisation === 0) {
          $annee = NULL;
        } else {
          $annee = $cotisation->getAnnee();
        }

        // récupération des roles de l'utilisateur
        // Si role role[0] est ROLE_USER alors chercher la région
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si l'identifiant de la region est différent de 1 (equipe regionale) alors recuperez son slug
          if ($gestionnaire->getRegion()->getId() !== 1 ) {
            $region = $gestionnaire->getRegion()->getId();
          }else{
            die('Veuillez patienter votre liste n\'est pas encore effective.');
          }
        }else{
          die('Veuillez patienter votre liste n\'est pas encore effective.');
        }

        $scout = new Scout();
        $form = $this->createForm('AppBundle\Form\ScoutType', $scout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($scout);
            $em->flush();

            $this->addFlash('notice', "La scout ".$scout->getNom()." a été créée avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a enregistré la scout '.$scout->getNom());

            return $this->redirectToRoute('imprime_region_liste');
        }

        $statut = NULL; $sexe = NULL; $fonction = NULL; $branche = NULL;//dump($branche);die();

        $scouts = $em->getRepository('AppBundle:Scout')->getAllScoutsByRegion($region, $annee, $statut, $sexe, $fonction, $branche);

        return $this->render('scout/liste.html.twig', array(
            'scouts' => $scouts,
            'scout' => $scout,
            'form' => $form->createView(),
            'statut'  => $statut,
            'sexe'  => $sexe,
            'fonction'  => $fonction,
            'branche' => $branche
        ));
    }

    /**
     * Impression de la liste des scouts de la region
     *
     * @Route("/pdf/region-{statut}-{sexe}-{fonction}-{branche}", name="imprime_region_liste_pdf")
     */
    public function imprimeRegionAction($statut, $sexe, $fonction, $branche)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();

      // Determination de l'année accademique encours
      $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();
      if ($cotisation === 0) {
        $annee = NULL;
      } else {
        $annee = $cotisation->getAnnee();
      }

      // récupération des roles de l'utilisateur
      // Si role role[0] est ROLE_USER alors chercher la région
      $roles[] = $user->getRoles();
      if ($roles[0][0] === 'ROLE_USER') {
        $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

        // Si l'identifiant de la region est différent de 1 (equipe regionale) alors recuperez son slug
        if ($gestionnaire->getRegion()->getId() !== 1 ) {
          $region = $gestionnaire->getRegion()->getId();
        }else{
          die('Veuillez patienter votre liste n\'est pas encore effective.');
        }
      }else{
        die('Veuillez patienter votre liste n\'est pas encore effective.');
      }

      if ($statut === 0 ) $statut = NULL;
      if ($sexe === 0 ) $sexe = NULL;
      if ($fonction === 0 ) $fonction = NULL;
      if ($branche === 0 ) $branche = NULL;

      $scouts = $em->getRepository('AppBundle:Scout')->getAllScoutsByRegion($region, $annee, $statut, $sexe, $fonction, $branche);

      $region = $em->getRepository('AppBundle:Region')->findOneById($region);

      $ref = strtoupper($gestionnaire->getRegion()->getNom()).': liste des scouts';
      $doc = strtoupper($gestionnaire->getRegion()->getNom()).' liste des scouts.pdf';

      $html = $this->renderView('imprime/liste_scout.html.twig', array(
        'scouts' => $scouts,
        'statut'  => $statut,
        'sexe'  => $sexe,
        'fonction'  => $fonction,
        'branche' => $branche,
        'entite'  => $region,
        'entiteLibelle' => "Région",
      ));
      $html2pdf = $this->get('html2pdf_factory')->create('P', 'A4', 'fr', true, 'UTF-8', array(10, 10, 10, 10));
      $html2pdf->pdf->SetAuthor('ASCCI');
      $html2pdf->pdf->SetTitle($ref);
      $html2pdf->pdf->SetSubject('Borderau cotisation nationale');
      //$html2pdf->pdf->SetKeywords('Bordereau,devandclick');
      $html2pdf->pdf->SetDisplayMode('real');
      $html2pdf->writeHTML($html);
      $html2pdf->Output($doc);

      $response = new Response();
      $response->headers->set('Content-type' , 'application/pdf');

      return $response;
    }


}
