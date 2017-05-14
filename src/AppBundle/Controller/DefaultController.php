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

        $user = $this->getUser();

      $date = new \DateTime();
        //$datet = new \DateTimeZone('Europe/Paris');

        // Envoie de mail de notification à l'administrateur
        $message = \Swift_Message::newInstance()
                  ->setSubject('ARFISGED Notifications')
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

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
