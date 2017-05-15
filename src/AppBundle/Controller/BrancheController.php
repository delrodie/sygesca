<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Branche;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Branche controller.
 *
 * @Route("admin/branche")
 */
class BrancheController extends Controller
{
    /**
     * Lists all branche entities.
     *
     * @Route("/", name="admin_branche_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $branche = new Branche();
        $form = $this->createForm('AppBundle\Form\BrancheType', $branche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($branche);
            $em->flush();

            $this->addFlash('notice', "La branche ".$branche->getNom()." a été créée avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a enregistré la branche '.$branche->getNom());

            return $this->redirectToRoute('admin_branche_index');
        }

        $branches = $em->getRepository('AppBundle:Branche')->findAll();

        return $this->render('branche/index.html.twig', array(
            'branches' => $branches,
            'branche' => $branche,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new branche entity.
     *
     * @Route("/new", name="admin_branche_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $branche = new Branche();
        $form = $this->createForm('AppBundle\Form\BrancheType', $branche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($branche);
            $em->flush();

            return $this->redirectToRoute('admin_branche_show', array('id' => $branche->getId()));
        }

        return $this->render('branche/new.html.twig', array(
            'branche' => $branche,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a branche entity.
     *
     * @Route("/{id}", name="admin_branche_show")
     * @Method("GET")
     */
    public function showAction(Branche $branche)
    {
        $deleteForm = $this->createDeleteForm($branche);

        return $this->render('branche/show.html.twig', array(
            'branche' => $branche,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing branche entity.
     *
     * @Route("/{id}/edit", name="admin_branche_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Branche $branche)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $deleteForm = $this->createDeleteForm($branche);
        $editForm = $this->createForm('AppBundle\Form\BrancheType', $branche);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "La branche ".$branche->getNom()." a été modifiée avec succès.!");

            $notification = $this->get('monolog.logger.notification');
            $notification->notice($user.' a modifiée la branche '.$branche->getNom());

            return $this->redirectToRoute('admin_branche_index');
        }

        $branches = $em->getRepository('AppBundle:Branche')->findAll();

        return $this->render('branche/edit.html.twig', array(
            'branche' => $branche,
            'branches' => $branches,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a branche entity.
     *
     * @Route("/{id}", name="admin_branche_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Branche $branche)
    {
        $form = $this->createDeleteForm($branche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($branche);
            $em->flush();
        }

        return $this->redirectToRoute('admin_branche_index');
    }

    /**
     * Creates a form to delete a branche entity.
     *
     * @param Branche $branche The branche entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Branche $branche)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_branche_delete', array('id' => $branche->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
