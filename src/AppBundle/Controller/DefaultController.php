<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}
