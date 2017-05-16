<?php

namespace AppBundle\Controller;

use AppBundle\Entity\District;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\ExpressionLanguage\Expression;

class AdminDistrictController extends Controller
{
    /**
     * Liste des districts.
     *
     * @Route("/district/admin", name="district_admin_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // récupération des roles de l'utilisateur
        // Si role role[0] est ROLE_USER alors chercher la région
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $region = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si region est différente de l'équipe nationale alors Accès non autorisé
          if (($region === NULL) || ($region->getRegion()->getId() != 1)) {
            throw new AccessDeniedException();
          }
        }

        $regions = $em->getRepository('AppBundle:Region')->findAll();

        return $this->render('default/admin_liste_region.html.twig', array(
            'regions' => $regions,
        ));
    }

    /**
     * Enregistrement d'un nouveau district de la région
     *
     * @Route("/district/admin-{id}-ajout-district", name="district_admin_ajout")
     * @Method({"GET", "POST"})
     */
    public function ajoutAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Si role user est différent de ROLE_ADMIN ou ROLE_SUPER_ADMIN alors accès réfusé
        $this->denyAccessUnlessGranted(new Expression(
        '"ROLE_ADMIN" in roles or (user and user.isSuperAdmin())'
        ));

        $district = new District();
        $form = $this->createForm('AppBundle\Form\DistrictAdminType', $district, array('region' => $id));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($district);
            $em->flush();

            return $this->redirectToRoute('district_admin_index');
        }

        $districts = $em->getRepository('AppBundle:District')->findBy(
                                                                    array('region' => $id),
                                                                    array('nom' => 'ASC')
                                                                );

        return $this->render('default/admin_district_new.html.twig', array(
            'districts' => $districts,
            'district' => $district,
            'form' => $form->createView(),
        ));
    }

    /**
     * Enregistrement d'un nouveau district de la région
     *
     * @Route("/district/admin-{region}-ajout-district-{slug}", name="district_admin_modif")
     * @Method({"GET", "POST"})
     */
    public function modifAction(Request $request, District $district, $region)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Si role user est différent de ROLE_ADMIN ou ROLE_SUPER_ADMIN alors accès réfusé
        $this->denyAccessUnlessGranted(new Expression(
        '"ROLE_ADMIN" in roles or (user and user.isSuperAdmin())'
        ));

        $deleteForm = $this->createDeleteForm($district);
        $editForm = $this->createForm('AppBundle\Form\DistrictAdminType', $district, array('region' => $region));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('district_admin_index');
        }

        $districts = $em->getRepository('AppBundle:District')->findBy(
                                                                    array('region' => $region),
                                                                    array('nom' => 'ASC')
                                                                );

        return $this->render('default/admin_district_edit.html.twig', array(
            'districts' => $districts,
            'district' => $district,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a district entity.
     *
     * @param District $district The district entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(District $district)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('district_delete', array('id' => $district->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Liste des districts par region
     *
     * @Route("/district/admin/liste{id}", name="district_admin_liste")
     * @Method("GET")
     */
    public function listeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $districts = $em->getRepository('AppBundle:District')
                        ->findBy(
                            array('region'  =>  $id),
                            array('nom' => 'ASC')
                        );
        return $this->render('default/liste_district.html.twig', array(
            'districts' => $districts,
        ));
    }
}
