<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Statut;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Statut controller.
 *
 * @Route("admin/statut")
 */
class StatutController extends Controller
{
    /**
     * Lists all statut entities.
     *
     * @Route("/", name="admin_statut_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $statut = new Statut();
        $form = $this->createForm('AppBundle\Form\StatutType', $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($statut);
            $em->flush();

            $this->addFlash('notice', "Le statut ".$statut->getLibelle()." a été crée avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a enregistré le statut '.$statut->getLibelle());

            return $this->redirectToRoute('admin_statut_index');
        }

        $notification = $this->get('monolog.logger.notification');
        $notification->notice($user.' a consulté la liste des statuts.');

        $statuts = $em->getRepository('AppBundle:Statut')->findAll();

        return $this->render('statut/index.html.twig', array(
            'statuts' => $statuts,
            'statut' => $statut,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new statut entity.
     *
     * @Route("/new", name="admin_statut_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $statut = new Statut();
        $form = $this->createForm('AppBundle\Form\StatutType', $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($statut);
            $em->flush();

            return $this->redirectToRoute('admin_statut_show', array('id' => $statut->getId()));
        }

        return $this->render('statut/new.html.twig', array(
            'statut' => $statut,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a statut entity.
     *
     * @Route("/{id}", name="admin_statut_show")
     * @Method("GET")
     */
    public function showAction(Statut $statut)
    {
        $deleteForm = $this->createDeleteForm($statut);

        return $this->render('statut/show.html.twig', array(
            'statut' => $statut,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing statut entity.
     *
     * @Route("/{id}/edit", name="admin_statut_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Statut $statut)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $deleteForm = $this->createDeleteForm($statut);
        $editForm = $this->createForm('AppBundle\Form\StatutType', $statut);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "Le statut ".$statut->getLibelle()." a été modifié avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a modifié le statut '.$statut->getLibelle());

            return $this->redirectToRoute('admin_statut_index');
        }

        $statuts = $em->getRepository('AppBundle:Statut')->findAll();

        return $this->render('statut/edit.html.twig', array(
            'statut' => $statut,
            'statuts' => $statuts,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a statut entity.
     *
     * @Route("/{id}", name="admin_statut_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Statut $statut)
    {
        $form = $this->createDeleteForm($statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($statut);
            $em->flush();
        }

        return $this->redirectToRoute('admin_statut_index');
    }

    /**
     * Creates a form to delete a statut entity.
     *
     * @param Statut $statut The statut entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Statut $statut)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_statut_delete', array('id' => $statut->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
