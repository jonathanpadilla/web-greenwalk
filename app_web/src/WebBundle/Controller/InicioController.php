<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InicioController extends Controller
{
    public function inicioAction(Request $request)
    {
        $session = $request->getSession();
        $admin   = ($session->get('admin'))? true: false;

    	$em      = $this->getDoctrine()->getManager();

        $productos      = $em->getRepository('WebBundle:Producto')->findBy(array('activo' => 1 ));
        $fotos_producto = $em->getRepository('WebBundle:ProductoFoto')->findBy(array('activo' => 1 ));
        $servicios      = $em->getRepository('WebBundle:Servicio')->findBy(array('activo' => 1 ));

        $lista_fotos = array();
        if($fotos_producto)
        {
            foreach($fotos_producto as $foto)
            {
                $lista_fotos[$foto->getProducto()->getId()][] = $foto->getFoto();
            }
            
        }

        // echo '<pre>';print_r($lista_fotos);exit;

        return $this->render('WebBundle::inicio.html.twig', array(
        	'session'       => $admin,
            'productos'     => $productos,
            'servicios'     => $servicios,
            'lista_fotos'   => $lista_fotos
        	));
    }
}