<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="admin_user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        $utilisateur = $this->getUser();
        $notification = $this->get('monolog.logger.notification');
        $notification->notice($utilisateur.' a consulté la liste des utilisateurs .');

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setEnabled(true);
            //Encodage du mot de passe
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $em->persist($user);
            $em->flush($user);

            $this->addFlash('notice', "L'utilisateur ".$user->getUsername()." a été crée avec succès.!");

            $utilisateur = $this->getUser();
            $notification = $this->get('monolog.logger.notification');
            $notification->notice($utilisateur.' a enregistré le nouvel utilisateur '.$user->getUsername());

            return $this->redirectToRoute('admin_user_index');
        }

        $utilisateur = $this->getUser();
        $notification = $this->get('monolog.logger.notification');
        $notification->notice($utilisateur.' a consulté la page d\'enregistrement des utilisateurs .');

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="admin_user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
          //$user->setEnabled(true);
          //Encodage du mot de passe
          $encoder = $this->container->get('security.password_encoder');
          $encoded = $encoder->encodePassword($user, $user->getPassword());
          $user->setPassword($encoded);

          $this->getDoctrine()->getManager()->flush();

          $utilisateur = $this->getUser();
          $notification = $this->get('monolog.logger.notification');
          $notification->notice($utilisateur.' a modifié l\'utilisateur '.$user->getUsername());

          return $this->redirectToRoute('admin_user_index');
        }

        $utilisateur = $this->getUser();
        $notification = $this->get('monolog.logger.notification');
        $notification->notice($utilisateur.' a envisagé modifier l\'utilisateur '.$user->getUsername());

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $utilisateur = $this->getUser();
            $notification = $this->get('monolog.logger.notification');
            $notification->notice($utilisateur.' a supprimé l\'utilisateur '.$user->getUsername());
        }

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
