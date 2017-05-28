<?php

namespace AppBundle\Controller;

use AppBundle\Entity\District;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\ExpressionLanguage\Expression;

class RegionalController extends Controller
{
    /**
     * Liste des districts.
     *
     * @Route("/groupes/liste", name="regional_district_liste")
     * @Method({"GET", "POST"})
     */
    public function listedistrictAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // récupération des roles de l'utilisateur
        // Si role role[0] est ROLE_USER alors chercher la région
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $region = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si user n'appartient à aucune région alors accès refusé
          // Sinon si user est à l'équipe nationale (Id = 1) alors renvoie à la page administrateur
          if (($region === NULL)) {
            throw new AccessDeniedException();
          } elseif (($region->getRegion()->getId() === 1)) {
            return $this->redirectToRoute('admin_groupe_liste');
          }
        }else {
          return $this->redirectToRoute('admin_groupe_liste');
        }

        $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user->getId()));

        // L'identification de la région de l'utilisateur
        $regionID = $gestionnaire->getRegion();
        $region = $em->getRepository('AppBundle:Region')->findOneById($regionID);

        // Liste des districts de la région
        $districts = $em->getRepository('AppBundle:District')->findBy(array('region' => $regionID));

        return $this->render('default/regional_district_liste.html.twig', array(
            'districts' => $districts,
            'region'  => $region,
        ));
    }

    /**
     * Liste des groupes par district
     *
     * @Route("/groupes/{district}-liste-details", name="regional_groupe_liste")
     */
    public function listegroupeAction($district)
    {
        $em = $this->getDoctrine()->getManager();

        $groupes = $em->getRepository('AppBundle:Groupe')
                        ->findBy(
                          array('district'  => $district),
                          array('paroisse'  => 'ASC')
                      );

        return $this->render('default/regional_groupe_liste.html.twig', array(
            'groupes' => $groupes,
        ));
    }

    /**
     * Liste des scouts adherants
     *
     * @Route("scouts-adherants-de-{cotisation}", name="bordereau_scouts_adherants")
     * @Method({"GET", "POST"})
     */
    public function adherantAction(Request $request, $cotisation)
    {
       $session = $request->getSession();
       $em = $this->getDoctrine()->getManager();
       $user = $this->getUser();

       // récupération des roles de l'utilisateur
       // Si role role[0] est ROLE_USER alors chercher la région
       $roles[] = $user->getRoles();
       if ($roles[0][0] === 'ROLE_USER') {
         $region = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

         // Si user n'appartient à aucune région alors accès refusé
         // Sinon si user est à l'équipe nationale (Id = 1) alors renvoie à la page administrateur
         if (($region === NULL)) {
           throw new AccessDeniedException();
         } elseif (($region->getRegion()->getId() === 1)) {
           $adherants = $em->getRepository('AppBundle:Scout')->getAdherant($cotisation);
         } else{
           $adherants = $em->getRepository('AppBundle:Scout')->getAdherantByRegion($region->getRegion(), $cotisation);
         }
       }else {
         $adherants = $em->getRepository('AppBundle:Scout')->getAdherant($cotisation);
       }

       // Si la session adhesion n'existe pas alors affecter la valeur NULL à nombre_adherant
       // sinon affecter le nombre_adherant
       if (!$session->has('adhesion')) {
          $nombre_adherant = NULL;
       } else {
          $nombre_adherant = count($session->get('adhesion'));
       }


       return $this->render('default/bordereau_scouts_adherants.html.twig', array(
          'adherants'  => $adherants,
          'cotisation'  => $cotisation,
          'nombre_adherant' => $nombre_adherant,
       ));
    }

    /**
     * Enregistrement en session des adherants s'ayant acquitté de la cotisation
     *
     * @Route("{scout}/nouveau-cotisant-{cotisation}", name="nouveau_cotisant")
     * @Method({"GET","POST"})
     */
    public function acquittantAction(Request $request, $scout, $cotisation)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Mise en session de la fonction de l'adherant
        if (!$session->has('adhesion')) $session->set('adhesion',array());
        $adhesion = $session->get('adhesion');

        // Mise en session de la branche du chef
        if (!$session->has('chefUnite')) $session->set('chefUnite',array());
        $chefUnite = $session->get('chefUnite');

        // Si la session existe alors modifier la valeur sinon ajouter les nouveaux adherants
        if ((array_key_exists($scout, $adhesion)) || (array_key_exists($scout, $chefUnite))) {
            if($request->query->get('fonction') != NULL) {
                $adhesion[$scout] = $request->query->get('fonction');
            }
            if($request->query->get('branche') != NULL) {
                $chefUnite[$scout] = $request->query->get('branche');
            }
        } else {
            if($request->query->get('fonction') != NULL){
                $adhesion[$scout] = $request->query->get('fonction');
            }else{
                return $this->redirectToRoute('adherant_cotisation', array("scout"  => $scout, "cotisation" => $cotisation));
            }
            if($request->query->get('branche') != NULL) {
                $chefUnite[$scout] = $request->query->get('branche');
            }
        }

        $session->set('adhesion',$adhesion);
        $session->set('chefUnite',$chefUnite);

        $cotisant = $em->getRepository('AppBundle:Scout')->findOneById($scout);
        $this->addFlash('notice', $cotisant->getNom()." ".$cotisant->getPrenoms()." a été ajouté avec succès au bordereau.");

        return $this->redirectToRoute('bordereau_scouts_adherants', array('cotisation' => $cotisation));

    }

    /**
     * Formulaire d'ajout en session de l'adherant
     *
     * @Route("{scout}/adherant-{cotisation}", name="adherant_cotisation")
     */
    public function saveadherantAction(Request $request, $scout, $cotisation)
    {
        $session = $request->getSession();

        if (!$session->has('adhesion')) $session->set('adhesion', array());
        $em = $this->getDoctrine()->getManager();
        $adherant = $em->getRepository('AppBundle:Scout')->findOneById($scout);
        $cotisants = $em->getRepository('AppBundle:Scout')->findArray(array_keys($session->get('adhesion')));
        $assurance = $em->getRepository('AppBundle:Cotisation')->findOneBy(array('annee'  => $cotisation));
        $adherants = $session->get('adhesion');

        return $this->render('default/borderau_save_adherant.html.twig', array(
            'cotisation'  => $cotisation,
            'adherant'  => $adherant,
            'cotisants' => $cotisants,
            'assurance' => $assurance,
            'adherants' => $adherants,
        ));
    }

    /**
     * Impression du bordereau non validé
     *
     * @Route("impression/bordereau{id}-{cotisation}", name="impression_bordereau_non_valide")
     */
    public function impressionbordereaunonvalideAction(Request $request, $id, $cotisation)
    {
        $em = $this->getDoctrine()->getManager();

        $bordereau = $em->getRepository('AppBundle:Bordereau')->findOneById($id);
        $assurance = $em->getRepository('AppBundle:Cotisation')->findOneBy(array('annee' => $cotisation));

        return $this->render('default/bordereau_regional_print.html.twig', array(
            'bordereau' => $bordereau,
            'assurance' => $assurance,
        ));
    }

    /**
     * Impression du bordereau non validé
     *
     * @Route("impression/bordereau-valide{id}-{cotisation}", name="impression_bordereau_valide")
     */
    public function impressionbordereauvalideAction(Request $request, $id, $cotisation)
    {
        $em = $this->getDoctrine()->getManager();

        $bordereau = $em->getRepository('AppBundle:Bordereau')->findOneBy(array('id' => $id, 'valide' => 1));
        $assurance = $em->getRepository('AppBundle:Cotisation')->findOneBy(array('annee' => $cotisation));

        if (!$bordereau) {
          throw new AccessDeniedException();
        }
        // La region par son code
        $regionCode = $bordereau->getCotisants()['region'];
        $region = $em->getRepository('AppBundle:Region')->findOneBy(array('code' => $regionCode));

        return $this->render('default/bordereau_print.html.twig', array(
            'bordereau' => $bordereau,
            'assurance' => $assurance,
            'region'  => $region,
        ));
    }

    /**
     * Numero cotiant du scout
     *
     * @Route("matricule{matricule}", name="scout_numero")
     */
    public function scoutnumeroAction($matricule)
    {
       $em = $this->getDoctrine()->getManager();

       $scout = $em->getRepository('AppBundle:Scout')->findOneBy(array('matricule'=> $matricule));

       return $this->render('default/scout_numero.html.twig', array(
          'scout' => $scout,
       ));
    }

}
