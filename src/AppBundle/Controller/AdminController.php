<?php

namespace AppBundle\Controller;

use AppBundle\Entity\District;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\ExpressionLanguage\Expression;

class AdminController extends Controller
{
    /**
     * Liste des districts et des groupes.
     *
     * @Route("/groupes/liste-par-region", name="admin_groupe_liste")
     * @Method({"GET", "POST"})
     */
    public function listeadmingroupeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // récupération des roles de l'utilisateur
        // Si role role[0] est ROLE_USER alors chercher la région
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si region est différente de l'équipe nationale alors Accès non autorisé
          if (($gestionnaire === NULL) || ($gestionnaire->getRegion()->getId() != 1)) {
            throw new AccessDeniedException();
          }
        }

        $district = new District();
        $form = $this->createForm('AppBundle\Form\GroupeAdminType', $district, array('user' => $user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Récupération de l'id de la région
            $regionID = $district->getRegion()->getId();
            $region = $em->getRepository('AppBundle:Region')->findOneById($regionID);

            // Si id est inférieur à 4 alors selection n'est pas une region
            /*if ($regionID < 4) {
              $this->addFlash('notice', "Désolé ".$region->getNom()." n'est pas une région.");
              return $this->redirectToRoute('admin_groupe_liste');
            }*/

            $groupes = $em->getRepository('AppBundle:Groupe')->getListeGroupeParRegion($regionID);

            return $this->render('default/admin_groupe_liste.html.twig', array(
                'groupes' => $groupes,
                'region'  => $region,
                'district' => $district,
                'form' => $form->createView(),
            ));

            //return $this->redirectToRoute('region_show', array('id' => $region->getId()));
        }

        $notification = $this->get('monolog.logger.notification');
        $notification->notice($user.' a consulté la liste des groupes.');

        // Liste de tous les groupes
        $groupes = $em->getRepository('AppBundle:Groupe')->findAll();
        $region = NULL;

        return $this->render('default/admin_groupe_liste.html.twig', array(
            'groupes' => $groupes,
            'region'  => $region,
            'district' => $district,
            'form' => $form->createView(),
        ));
    }

}
