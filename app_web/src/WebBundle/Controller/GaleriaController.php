<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WebBundle\Entity\Imagen;

class GaleriaController extends Controller
{
    public function galeriaAction(Request $request)
    {
        $session = $request->getSession();
        $admin   = ($session->get('admin'))? true: false;

    	$em      = $this->getDoctrine()->getManager();

        $lista_imagen = $em->getRepository('WebBundle:Imagen')->findBy(array('activo' => 1 ));

        return $this->render('WebBundle::galeria.html.twig', array(
        	'session'   => $admin,
            'lista_imagen' => $lista_imagen,
            'activo'    => 'galeria'
        	));
    }

    public function subirImagenAction(Request $request)
    {
        $result = true;

        $imagen = $this->subirImagen($request->files->get('imagen', null));

        $em = $this->getDoctrine()->getManager();

        $foto = new Imagen();
        $foto->setImagen($imagen);
        $foto->setActivo(1);
        $em->persist($foto);
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