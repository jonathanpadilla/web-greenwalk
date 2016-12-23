<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactoController extends Controller
{
    public function contactoAction(Request $request)
    {
        $session = $request->getSession();
        $admin   = ($session->get('admin'))? true: false;

    	$em      = $this->getDoctrine()->getManager();

        return $this->render('WebBundle::contacto.html.twig', array(
        	'session'   => $admin,
            'activo'    => 'contacto'
        	));
    }
}