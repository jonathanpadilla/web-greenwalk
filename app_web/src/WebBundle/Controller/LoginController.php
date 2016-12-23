<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    public function loginAction()
    {
        return $this->render('WebBundle::ingreso.html.twig', array(
        	'error' => ''));
    }
    public function loginCheckAction(Request $request)
    {
    	if($request->getMethod('post'))
    	{
    		$em 		= $this->getDoctrine()->getManager();
    		$usuario 	= $request->get('usuario', false);
    		$clave 		= $request->get('clave', false);

    		if($usuario = $em->getRepository('WebBundle:Usuario')->findOneBy(array('usuario' => $usuario)))
    		{
    			$claveReal = $usuario->getClave();

    			if($clave == $claveReal)
    			{
    				$session = $request->getSession();
                    $session->set('admin', true);

    				return $this->redirectToRoute('web_inicio');
    			}else{
    				return $this->render('WebBundle::ingreso.html.twig', array(
			        	'error' => 'Clave incorrecta'
			        	));
    			}

    		}else{
    			return $this->render('WebBundle::ingreso.html.twig', array(
		        	'error' => 'Usuario incorrecto'
		        	));
    		}

    	}
    }

    public function logoutAction(Request $request)
    {
    	$session = $request->getSession();
    	
    	$admin = ($session->get('admin'))?true:false;

    	$session->remove('admin');

    	return $this->redirectToRoute('web_inicio');
    }
}