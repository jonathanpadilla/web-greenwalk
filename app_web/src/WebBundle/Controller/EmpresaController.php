<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmpresaController extends Controller
{
    public function empresaAction(Request $request)
    {
        $session = $request->getSession();
        $admin   = ($session->get('admin'))? true: false;

    	$em      = $this->getDoctrine()->getManager();

        return $this->render('WebBundle::empresa.html.twig', array(
        	'session'   => $admin,
            'activo'    => 'empresa'
        	));
    }
}