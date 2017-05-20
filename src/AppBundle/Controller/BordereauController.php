<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bordereau;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Bordereau controller.
 *
 * @Route("bordereau")
 */
class BordereauController extends Controller
{
    /**
     * Lists all bordereau entities.
     *
     * @Route("/liste-de-{cotisation}", name="bordereau_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, $cotisation)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $user =$this->getUser();

        // récupération des roles de l'utilisateur
        // Si role role[0] est ROLE_USER alors chercher la région
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si user n'appartient à aucune région alors accès refusé
          // Sinon si user est à l'équipe nationale (Id = 1) alors renvoie à la page administrateur
          if (($gestionnaire === NULL)) {
            throw new AccessDeniedException();
          } elseif (($gestionnaire->getRegion()->getId() === 1)) {
            $finance = $em->getRepository('AppBundle:Gestionnaire')->getFinance($gestionnaire->getId());
            if ($finance) {
              $bordereaus = $em->getRepository('AppBundle:Bordereau')->findAll();
              $valid = true;
            } else {
              throw new AccessDeniedException();
            }

          } else{
            $regionID = $gestionnaire->getRegion()->getId();
            $region = $em->getRepository('AppBundle:Region')->findOneById($regionID);
            $bordereaux = $em->getRepository('AppBundle:Bordereau')->findAll();
            //dump($bordereaux);die();
            foreach ($bordereaux as $bordereau) {
              if (($bordereau->getCotisants()['region']) === ($region->getCode())) {
                $bordereaus[] = $bordereau;
                  //dump($bordereaus);die();
              } else {
                $bordereaus[] = null;
              }
            }
            $valid = false;
          }
        }else {
          $bordereaus = $em->getRepository('AppBundle:Bordereau')->findAll();
          $valid = true;
        }//dump($bordereaus);die();

        return $this->render('bordereau/index.html.twig', array(
            'bordereaus' => $bordereaus,
            'cotisation'  => $cotisation,
            'valid' => $valid,
        ));
    }

    /**
     * Creates a new bordereau entity.
     *
     * @Route("/new/{cotisation}", name="bordereau_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $cotisation)
    {

        $session = $request->getSession();

        $bordereau = new Bordereau();
        $form = $this->createForm('AppBundle\Form\BordereauType', $bordereau, array('cotisation' => $cotisation));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Code du bordereau
            $code1 = substr($cotisation, -2, 2);
            $code2 = substr($cotisation, -7, 2);
            $numero = $em->getRepository('AppBundle:Bordereau')->getNumeroOrdre();
            $code = 'B'.$code2.''.$code1.'-'.$numero;
            $bordereau->setNumero($code);
            $bordereau->setvalide(0);
            $bordereau->setCotisants($this->validation($request, $cotisation));
            $em->persist($bordereau);
            $em->flush();

            // Destruction des sessions
            $session->remove('adhesion');


            return $this->redirectToRoute('impression_bordereau_non_valide', array('id' => $bordereau->getId(), 'cotisation' => $cotisation));
        }

        if (!$session->has('adhesion')) $session->set('adhesion', array());
        $em = $this->getDoctrine()->getManager();
        //$adherant = $em->getRepository('AppBundle:Scout')->findOneById($scout);
        $cotisants = $em->getRepository('AppBundle:Scout')->findArray(array_keys($session->get('adhesion')));
        $assurance = $em->getRepository('AppBundle:Cotisation')->findOneBy(array('annee'  => $cotisation));
        $adherants = $session->get('adhesion');


        return $this->render('bordereau/new.html.twig', array(
            'bordereau' => $bordereau,
            'form' => $form->createView(),
            'cotisants' => $cotisants,
            'adherants' => $adherants,
            'assurance' => $assurance,
        ));
    }

    /**
     * Finds and displays a bordereau entity.
     *
     * @Route("/{id}", name="bordereau_show")
     * @Method("GET")
     */
    public function showAction(Bordereau $bordereau)
    {
        $deleteForm = $this->createDeleteForm($bordereau);

        return $this->render('bordereau/show.html.twig', array(
            'bordereau' => $bordereau,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bordereau entity.
     *
     * @Route("/{id}/edit/{cotisation}", name="bordereau_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bordereau $bordereau, $cotisation)
    {
        $deleteForm = $this->createDeleteForm($bordereau);
        $editForm = $this->createForm('AppBundle\Form\BordereauType', $bordereau, array('cotisation' => $cotisation));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $bordereau->setValide(1);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bordereau_index', array('cotisation' => $cotisation));
        }

        $regionCode = $bordereau->getCotisants()['region'];

        $em = $this->getDoctrine()->getManager();
        $assurance = $em->getRepository('AppBundle:Cotisation')->findOneBy(array('annee' => $cotisation));
        $region = $em->getRepository('AppBundle:Region')->findOneBy(array('code' => $regionCode));

        return $this->render('bordereau/edit.html.twig', array(
            'bordereau' => $bordereau,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'cotisation'=> $cotisation,
            'assurance' => $assurance,
            'region'  => $region,
        ));
    }

    /**
     * Deletes a bordereau entity.
     *
     * @Route("/{id}", name="bordereau_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bordereau $bordereau)
    {
        $form = $this->createDeleteForm($bordereau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bordereau);
            $em->flush();
        }

        return $this->redirectToRoute('bordereau_index');
    }

    /**
     * Creates a form to delete a bordereau entity.
     *
     * @param Bordereau $bordereau The bordereau entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bordereau $bordereau)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bordereau_delete', array('id' => $bordereau->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Validation des adherants sur les bordereaux
     * Mise a jour des information des adherant dans la table scout
     */
    public function validation($request, $cotisation)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $adherants = $session->get('adhesion');
        $bordereau = array();
        $montantTotal = 0;

        $cotisants = $em->getRepository('AppBundle:Scout')->findArray(array_keys($session->get('adhesion')));
        $assurance = $em->getRepository('AppBundle:Cotisation')->findOneBy(array('annee'  => $cotisation));

        foreach ($cotisants as $cotisant) {

          $fonction = $adherants[$cotisant->getId()];
          if (($fonction === 'Louveteau') or ($fonction === 'Eclaireur') or ($fonction === 'Cheminot') or ($fonction === 'Routier'))
              $montant = $assurance->getJeune();
          elseif (($fonction === 'CU') or ($fonction === 'CG'))
              $montant = $assurance->getGroupe();
          elseif (($fonction === 'CD') or ($fonction === 'ED'))
              $montant = $assurance->getDistrict();
          else
              $montant = $assurance->getCadre();

          $montantTotal += $montant;

          $bordereau['scout'][$cotisant->getId()] = array(
              'matricule' =>  $cotisant->getMatricule(),
              'nom' =>  $cotisant->getNom(),
              'prenoms'=> $cotisant->getPrenoms(),
              'date' =>  $cotisant->getDatenaiss(),
              'lieu'  =>  $cotisant->getLieuNaiss(),
              'sexe' =>  $cotisant->getSexe(),
              'fonction' =>  $fonction,
              'branche'  => $cotisant->getBranche()->getNom(),
              'montant' => $montant,
          );

          // Mise a jour du numéro de cotisation du scout
          // Recueration du code de la région
          $region = $em->getRepository('AppBundle:Region')->getRegionCode($cotisant->getGroupe());

          // Code scout
          $value = $cotisant->getId();
          if ($value < 10) {
             $num = '000'.$value;
          } elseif ($value < 100){
             $num = '00'.$value;
          } elseif ($value < 1000){
             $num = '0'.$value;
          } else{
             $num = $value;
          }

          $anne = substr($cotisation, -2, 2);

          $numero = $region.'-'.$num.'-'.$anne;

          $cotisant->setNumero($numero);
          $cotisant->setCotisation($cotisation);
          $em->persist($cotisant);
          $em->flush();

          $bordereau['region'] = $region;


        }

        return $bordereau;
    }
}
