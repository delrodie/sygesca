<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Scout;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Scout controller.
 *
 * @Route("scout")
 */
class ScoutController extends Controller
{
    /**
     * Lists all scout entities.
     *
     * @Route("/", name="scout_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // récupération des roles de l'utilisateur
        // Si role role[0] est ROLE_USER alors chercher la région
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si user n'appartient à aucune région alors accès refusé
          // Sinon si user est à l'équipe nationale (Id = 1) alors renvoie afficher la liste de tous les scouts
          if (($gestionnaire === NULL)) {
            throw new AccessDeniedException();
          } elseif (($gestionnaire->getRegion()->getId() === 1)) {
            $scouts = $em->getRepository('AppBundle:Scout')->findAll();
          } else{
            $region = $em->getRepository('AppBundle:Gestionnaire')
                          ->findOneBy(array('user'  => $user))
                          ->getRegion()
                          ;

            $scouts = $em->getRepository('AppBundle:Scout')->findScoutByUser($region->getId());
          }
        }else {
          $scouts = $em->getRepository('AppBundle:Scout')->findAll();
        }



        return $this->render('scout/index.html.twig', array(
            'scouts' => $scouts,
        ));
    }

    /**
     * Creates a new scout entity.
     *
     * @Route("/new", name="scout_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $scout = new Scout();
        $user = $this->getUser();

        $form = $this->createForm('AppBundle\Form\ScoutType', $scout, array('user' => $user));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // L'unicite du scout dans le système
            // si nom et prenoms et datenaissance existe alors ce scout deja identifé a nom region
            $nom = $scout->getNom(); $prenoms = $scout->getPrenoms();
            $date = $scout->getDatenaiss(); $lieu = $scout->getLieunaiss();
            $verif = $em->getRepository('AppBundle:Scout')->uniciteScout($nom, $prenoms, $date, $lieu);

            if ($verif) {

              $editForm = $this->createForm('AppBundle\Form\ScoutType', $scout, array('user' => $user));
              $editForm->handleRequest($request);

              $nom = $scout->getNom(); $prenoms = $scout->getPrenoms();
              $region = $verif->getGroupe()->getDistrict()->getRegion()->getNom();
              $identite = $scout->getNom()." ".$scout->getPrenoms();
              $naissance = " née le ".$scout->getDatenaiss()." à ".$scout->getLieunaiss();
              $message = $identite." ".$naissance." est déjà enregistré(e) à ".$region.".<br> Veuillez
                          faire une demande de transfert, pour valider sa cotisation.";

              $this->addFlash('notice', $message);

                return $this->render('default/scout_existe.html.twig', array(
                    'verif' => $verif,
                    'scout' => $scout,
                    'form' => $editForm->createView(),
                ));
            }

            // Generation du matricule
            $matricule = $this->container->get('scout_matricule')->matricule($scout->getGroupe()); // Service
            $scout->setMatricule($matricule);
            $scout->setCotisation(0);
            $scout->setNumero(0);

            $this->addFlash('notice', $scout->getNom()." ".$scout->getPrenoms(). " a été enregisté(e) avec succès.");

            $em->persist($scout);
            $em->flush();

            return $this->redirectToRoute('scout_show', array('slug'  => $scout->getSlug()));
        }

        return $this->render('scout/new.html.twig', array(
            'scout' => $scout,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a scout entity.
     *
     * @Route("/{slug}", name="scout_show")
     * @Method("GET")
     */
    public function showAction(Scout $scout)
    {
        $deleteForm = $this->createDeleteForm($scout);

        return $this->render('scout/show.html.twig', array(
            'scout' => $scout,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing scout entity.
     *
     * @Route("/{slug}/edit", name="scout_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Scout $scout)
    {
        $user = $this->getUser();

        $deleteForm = $this->createDeleteForm($scout);
        $editForm = $this->createForm('AppBundle\Form\ScoutType', $scout, array('user' => $user));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', $scout->getNom()." ".$scout->getPrenoms(). " a été modifié(e) avec succès.");

            return $this->redirectToRoute('scout_show', array('slug'  => $scout->getSlug()));
        }

        return $this->render('scout/edit.html.twig', array(
            'scout' => $scout,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a scout entity.
     *
     * @Route("/{id}", name="scout_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Scout $scout)
    {
        $form = $this->createDeleteForm($scout);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($scout);
            $em->flush();
        }

        return $this->redirectToRoute('scout_index');
    }

    /**
     * Creates a form to delete a scout entity.
     *
     * @param Scout $scout The scout entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Scout $scout)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('scout_delete', array('id' => $scout->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
