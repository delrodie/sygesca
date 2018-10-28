<?php

namespace AppBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class BordereauController extends Controller
{
    /**
     * Annulation de bordereau
     *
     * @Route("/annulation-{cotisation}", name="frontend_annulation")
     */
    public function annulationAction(Request $request, $cotisation)
    {
        $session = $request->getSession();
        $session->remove('adhesion');

        return $this->redirectToRoute('bordereau_scouts_adherants', array('cotisation'=>$cotisation));
    }
}