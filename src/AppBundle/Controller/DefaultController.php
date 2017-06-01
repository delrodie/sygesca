<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $userIp = $request->getClientIp();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        // Si la session user_connecte n'existe alors logger user vient de se connecter
        // sinon user consulte le tableau de bord
        if (!$session->has('userConnecte')) {
          $user = $this->getUser();
          $notification = $this->get('monolog.logger.notification');
          $notification->notice($user.' vient de se connecté sur la plateforme avec l\'ip: '.$userIp);

          $date = new \DateTime();
          //$datet = new \DateTimeZone('Europe/Paris');

          // Envoie de mail de notification à l'administrateur
          $message = \Swift_Message::newInstance()
                    ->setSubject('SYGESCA Notifications')
                    ->setFrom('noreply@arfis.com')
                    ->setTo('delrodieamoikon@gmail.com')
                    ->setBody(
                    $this->renderView('emails/connexion.html.twig', array(
                        'user' => $user,
                        'ip' => $userIp,
                        'date_connexion'  => $date->format('D d M Y h:i:s'),
                      )),
                        'text/html'
                    );
          $this->get('mailer')->send($message);

          // Enregistrement de la session
          $session->set('userConnecte', $this->getUser());

        } else {
          $user = $this->getUser();
          $notification = $this->get('monolog.logger.notification');
          $notification->notice($user.' a consulté le tableau de bord .');
        }

        $regions = $em->getRepository('AppBundle:Region')->findAll();
        // Determination de l'année accademique encours
        $cotisation = $em->getRepository('AppBundle:Cotisation')->getDerniereCotisation();
        if ($cotisation === 0) {
          $annee = NULL;
        } else {
          $annee = $cotisation->getAnnee();
        }

        // récupération des roles de l'utilisateur
        // Si role role[0] est ROLE_USER alors chercher la région
        $roles[] = $user->getRoles();
        if ($roles[0][0] === 'ROLE_USER') {
          $gestionnaire = $em->getRepository('AppBundle:Gestionnaire')->findOneBy(array('user' => $user));

          // Si l'identifiant de la region est supérieur a 4 alors revoyer a la view des regions
          if ($gestionnaire->getRegion()->getId() > 4 ) {
            $regionID = $gestionnaire->getRegion()->getId();

            $districts = $em->getRepository('AppBundle:District')->findBy(array('region' => $regionID), array('nom'  => 'ASC'));

            return $this->render('default/index_region.html.twig', [
                'districts' => $districts,
                'annee' => $annee,
                'regionID'  => $regionID,
            ]);
          }
        }

        return $this->render('default/index.html.twig', [
            'regions' => $regions,
            'annee' => $annee,
        ]);
    }

    /**
     * Liste des cotisations
     *
     * @Route("/cotisation/liste", name="cotisation_liste")
     */
    public function cotisationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $cotisations = $em->getRepository('AppBundle:Cotisation')->findAll();

        return $this->render('default/cotisation_liste.html.twig', array(
            'cotisations' => $cotisations,
        ));
    }

    /**
     * Factures des
     * @Route("/facture", name="facture")
     */
    public function factureAction()
    {
      $em = $this->getDoctrine()->getManager();

      //$users = $em->getRepository('AppBundle:User')->findAll();

      //$html = $this->renderView('statistiques/nombres.html.twig', array('nombre' => 25));
      $html = $this->renderView('imprime/modele.html.twig');
      $html2pdf = $this->get('html2pdf_factory')->create('L', 'A4', 'fr', true, 'UTF-8', array(10, 10, 10, 10));
      $html2pdf->pdf->SetAuthor('EdenArt');
        $html2pdf->pdf->SetTitle('SyGesCa ');
        $html2pdf->pdf->SetSubject('Facture DevAndClick');
        $html2pdf->pdf->SetKeywords('facture,devandclick');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        $html2pdf->Output('Facture.pdf');

        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');

        return $response;

    }
}
