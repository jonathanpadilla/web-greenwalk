<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WebBundle\Entity\Servicio;

class ServiciosController extends Controller
{
    public function serviciosAction(Request $request)
    {
        $session = $request->getSession();
        $admin   = ($session->get('admin'))? true: false;

    	$em      = $this->getDoctrine()->getManager();

        $lista_servicio = $em->getRepository('WebBundle:Servicio')->findBy(array('activo' => 1 ));

        return $this->render('WebBundle::servicios.html.twig', array(
        	'session'           => $admin,
            'lista_servicio'    => $lista_servicio,
            'activo'            => 'servicios'
        	));
    }

    public function guardarServicioAction(Request $request)
    {
        $result = true;

        $titulo = $request->get('titulo', false);
        $descripcion = $request->get('descripcion', false);
        $foto = $this->subirImagen($request->files->get('foto', null));
        $iconos = $this->subirImagen($request->files->get('iconos', null));

        $em = $this->getDoctrine()->getManager();

        $servicio = new Servicio();
        $servicio->setTitulo($titulo);
        $servicio->setTexto($descripcion);
        $servicio->setFoto($foto);
        $servicio->setIcono($iconos);
        $servicio->setActivo(1);
        $em->persist($servicio);
        $em->flush();

        echo json_encode(array('result' => $result));
        exit;
    }

    public function eliminarServicioAction(Request $request)
    {
        $result = true;

        $id = $request->get('id', false);
        $em = $this->getDoctrine()->getManager();

        $servicio = $em->getRepository('WebBundle:Servicio')->findOneBy(array('id' => $id ));
        $servicio->setActivo(0);
        $em->persist($servicio);
        $em->flush();

        echo json_encode(array('result' => $result));
        exit;
    }

    private function subirImagen($foto)
    {
        $result = null;
        if($foto)
        {
            $obj = array(
                'filesize'      => $foto->getClientSize(),
                'filetype'      => $foto->getClientMimeType(),
                'fileextension' => $foto->getClientOriginalExtension(),
                'filenewname'   => uniqid().".".$foto->getClientOriginalExtension(),
                'filenewpath'   => __DIR__.'/../../../../image/uploads'
            );
            if($obj['filesize'] <= 5242880 && ($obj['filetype'] == 'image/png' || $obj['filetype'] == 'image/jpeg') )
            {
                $foto->move($obj['filenewpath'], $obj['filenewname']);
                $result = $obj['filenewname'];
            }
        }
        return $result;
    }
}