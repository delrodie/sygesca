<?php

namespace AppBundle\Controller;

use AppBundle\Entity\District;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * District controller.
 *
 * @Route("district")
 */
class DistrictController extends Controller
{
    /**
     * Lists all district entities.
     *
     * @Route("/", name="district_index")
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

          // Si user n'appartient à aucune région alors accès refusé
          // Sinon si user est à l'équipe nationale (Id = 1) alors renvoie à la page administrateur
          if (($region === NULL)) {
            throw new AccessDeniedException();
          } elseif (($region->getRegion()->getId() === 1)) {
            return $this->redirectToRoute('district_admin_index');
          }
        }else {
          return $this->redirectToRoute('district_admin_index');
        }

        $district = new District();
        $form = $this->createForm('AppBundle\Form\DistrictType', $district, array('user' => $user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($district);
            $em->flush();

            $this->addFlash('notice', "Le district ".$district->getNom()." a été crée avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a enregistré le district '.$district->getNom());

            return $this->redirectToRoute('district_index');
        }

        // Recherche de la région de l'utilisateur
        // Recherche des districts de la région
        //$region = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

        $districts = $em->getRepository('AppBundle:District')->findBy(
                                                                    array('region' => $region->getRegion()->getId()),
                                                                    array('nom' => 'ASC')
                                                                );

        $notification = $this->get('monolog.logger.notification');
        $notification->notice($user.' a consulté la liste des districts.');

        return $this->render('district/index.html.twig', array(
            'districts' => $districts,
            'district' => $district,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new district entity.
     *
     * @Route("/new", name="district_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $district = new District();
        $user = $this->getUser();
        $form = $this->createForm('AppBundle\Form\DistrictType', $district, array('user' => $user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($district);
            $em->flush();

            return $this->redirectToRoute('district_index');
        }

        return $this->render('district/new.html.twig', array(
            'district' => $district,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a district entity.
     *
     * @Route("/{slug}", name="district_show")
     * @Method("GET")
     */
    public function showAction(District $district)
    {
        $deleteForm = $this->createDeleteForm($district);

        return $this->render('district/show.html.twig', array(
            'district' => $district,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing district entity.
     *
     * @Route("/{slug}/edit", name="district_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, District $district)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $deleteForm = $this->createDeleteForm($district);
        $editForm = $this->createForm('AppBundle\Form\DistrictType', $district, array('user' => $user));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "Le district ".$district->getNom()." a été modifié avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a modifié le district '.$district->getNom());

            return $this->redirectToRoute('district_index');
        }

        // Recherche de la région de l'utilisateur
        // Recherche des districts de la région
        $region = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

        $districts = $em->getRepository('AppBundle:District')->findBy(
                                                                    array('region' => $region->getRegion()->getId()),
                                                                    array('nom' => 'ASC')
                                                                );

        return $this->render('district/edit.html.twig', array(
            'district' => $district,
            'districts' => $districts,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a district entity.
     *
     * @Route("/{id}", name="district_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, District $district)
    {
        $user = $this->getUser();

        $form = $this->createDeleteForm($district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($district);
            $em->flush();

            $this->addFlash('notice', "Le district ".$district->getNom()." a été supprimé avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a supprimé le district '.$district->getNom());
        }

        return $this->redirectToRoute('district_index');
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
}
