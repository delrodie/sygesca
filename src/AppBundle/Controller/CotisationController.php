<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cotisation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cotisation controller.
 *
 * @Route("admin/cotisation")
 */
class CotisationController extends Controller
{
    /**
     * Lists all cotisation entities.
     *
     * @Route("/", name="admin_cotisation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cotisations = $em->getRepository('AppBundle:Cotisation')->findAll();

        return $this->render('cotisation/index.html.twig', array(
            'cotisations' => $cotisations,
        ));
    }

    /**
     * Creates a new cotisation entity.
     *
     * @Route("/new", name="admin_cotisation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cotisation = new Cotisation();
        $form = $this->createForm('AppBundle\Form\CotisationType', $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cotisation);
            $em->flush();

            $this->addFlash('notice', "Les montants de la cotisation de cette année ".$cotisation->getAnnee()." ont été enregistrés avec succès.!");

            return $this->redirectToRoute('admin_cotisation_index');
        }

        return $this->render('cotisation/new.html.twig', array(
            'cotisation' => $cotisation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cotisation entity.
     *
     * @Route("/{id}", name="admin_cotisation_show")
     * @Method("GET")
     */
    public function showAction(Cotisation $cotisation)
    {
        $deleteForm = $this->createDeleteForm($cotisation);

        return $this->render('cotisation/show.html.twig', array(
            'cotisation' => $cotisation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cotisation entity.
     *
     * @Route("/{slug}/edit", name="admin_cotisation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cotisation $cotisation)
    {
        $deleteForm = $this->createDeleteForm($cotisation);
        $editForm = $this->createForm('AppBundle\Form\CotisationType', $cotisation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_cotisation_index');
        }

        return $this->render('cotisation/edit.html.twig', array(
            'cotisation' => $cotisation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cotisation entity.
     *
     * @Route("/{id}", name="admin_cotisation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cotisation $cotisation)
    {
        $form = $this->createDeleteForm($cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cotisation);
            $em->flush();
        }

        return $this->redirectToRoute('admin_cotisation_index');
    }

    /**
     * Creates a form to delete a cotisation entity.
     *
     * @param Cotisation $cotisation The cotisation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cotisation $cotisation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_cotisation_delete', array('id' => $cotisation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
