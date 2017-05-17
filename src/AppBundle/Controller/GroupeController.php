<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Groupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Groupe controller.
 *
 * @Route("groupe")
 */
class GroupeController extends Controller
{
    /**
     * Lists all groupe entities.
     *
     * @Route("/{districtID}", name="groupe_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, $districtID)
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

        $groupe = new Groupe();
        $form = $this->createForm('AppBundle\Form\GroupeType', $groupe, array('district' => $districtID));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            return $this->redirectToRoute('groupe_index', array('districtID'  => $districtID));
        }

        $groupes = $em->getRepository('AppBundle:Groupe')
                      ->findBy(
                        array('district'  => $districtID),
                        array('paroisse' => 'ASC')
                      );

        return $this->render('groupe/index.html.twig', array(
            'groupes' => $groupes,
            'groupe' => $groupe,
            'form' => $form->createView(),
            'district'  => $districtID,
        ));
    }

    /**
     * Creates a new groupe entity.
     *
     * @Route("/new", name="groupe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $groupe = new Groupe();
        $form = $this->createForm('AppBundle\Form\GroupeType', $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            return $this->redirectToRoute('groupe_show', array('id' => $groupe->getId()));
        }

        return $this->render('groupe/new.html.twig', array(
            'groupe' => $groupe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a groupe entity.
     *
     * @Route("/{id}", name="groupe_show")
     * @Method("GET")
     */
    public function showAction(Groupe $groupe)
    {
        $deleteForm = $this->createDeleteForm($groupe);

        return $this->render('groupe/show.html.twig', array(
            'groupe' => $groupe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing groupe entity.
     *
     * @Route("/{slug}/edit/{districtID}", name="groupe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Groupe $groupe, $districtID)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        /*
         * Si district n'appartient pas à la region de l'user alors accès réfusé
         * récupération des roles de l'utilisateur
         * Si role role[0] est ROLE_USER alors chercher la région
         */
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si user n'appartient à aucune région alors accès refusé
          // Sinon si user est à l'équipe nationale (Id = 1) alors renvoie à la page administrateur
          if (($gestionnaire === NULL)) {
            throw new AccessDeniedException();
          } elseif (($gestionnaire->getRegion()->getId() === 1)) {
            return $this->redirectToRoute('district_admin_index');
          } else{
            // Si region du district est different de region du gestionnaire alors accès refusé
            $district = $em->getRepository('AppBundle:District')->findOneById($districtID);
            if (($gestionnaire->getRegion()->getId()) != ($district->getRegion()->getId())) {
              throw new AccessDeniedException();
            }
          }
        }else {
          return $this->redirectToRoute('district_admin_index');
        }

        $deleteForm = $this->createDeleteForm($groupe);
        $editForm = $this->createForm('AppBundle\Form\GroupeType', $groupe, array('district' => $districtID));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groupe_index', array('districtID'  => $districtID));
        }

        $groupes = $em->getRepository('AppBundle:Groupe')
                      ->findBy(
                        array('district'  => $districtID),
                        array('paroisse' => 'ASC')
                      );

        return $this->render('groupe/edit.html.twig', array(
            'groupe' => $groupe,
            'groupes' => $groupes,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'district'  => $districtID,
        ));
    }

    /**
     * Deletes a groupe entity.
     *
     * @Route("/{id}", name="groupe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Groupe $groupe)
    {
        $form = $this->createDeleteForm($groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupe);
            $em->flush();
        }

        return $this->redirectToRoute('groupe_index');
    }

    /**
     * Creates a form to delete a groupe entity.
     *
     * @param Groupe $groupe The groupe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Groupe $groupe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groupe_delete', array('id' => $groupe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
