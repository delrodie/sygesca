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

}
