<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WebBundle\Entity\Cupcakes;
use WebBundle\Entity\Banner;

class AdminController extends Controller
{
    // FUNCIONES AJAX
    public function guardarTextoAction(Request $request)
    {
    	// variables
    	$result = false;
    	$id 	= ($request->get('id', false))		?$request->get('id')	:null;
    	$campo 	= ($request->get('campo', false))	?$request->get('campo')	:null;
    	$texto 	= ($request->get('texto', false))	?$request->get('texto')	:null;
    	$em 	= $this->getDoctrine()->getManager();

    	// actualizar texto
    	if( is_numeric($id) && $seccion = $em->getRepository('WebBundle:Seccion')->findOneBy(array('id' => $id )) )
    	{
    		switch($campo)
    		{
    			case 1: $seccion->setTexto1($texto);break;
    			case 2: $seccion->setTexto2($texto);break;
    			case 3: $seccion->setTexto3($texto);break;
    			case 4: $seccion->setTexto4($texto);break;
    		}

    		$em->persist($seccion);
    		$em->flush();

    		$result = true;
    	}

    	echo json_encode(array('result' => $result));
    	exit;
    }

    public function guardarImagenAction(Request $request)
    {
        $result = false;
        
        if($request->getMethod('post'))
        {
            $id     = ($request->get('id', false))? $request->get('id'): 0;
            $foto   = ($request->files->get('foto', false))? $request->files->get('foto', false): null;
            $em     = $this->getDoctrine()->getManager();

            if($img = $this->subirImagen($foto))
            {
                if( is_numeric($id) && $seccion = $em->getRepository('WebBundle:Seccion')->findOneBy(array('id' => $id )) )
                {
                    $seccion->setFoto($img);
                    $em->persist($seccion);
                    $em->flush();
                }
            }
            
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function guardarFilaSeccionAction(Request $request)
    {
        $result = false;
        $em     = $this->getDoctrine()->getManager();
        $id     = ($request->get('id', false))? $request->get('id'): 0;
        $datos  = ($request->get('datos', false))? $request->get('datos'): 0;

        if( is_numeric($id) && $seccion = $em->getRepository('WebBundle:Seccion')->findOneBy(array('id' => $id )) )
        {
            if( isset($datos['texto1']) )
                $seccion->setTexto1($datos['texto1']);

            $em->persist($seccion);
            $em->flush();
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    private function subirImagen($foto)
    {
        $result = false;

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

                $result = 'image/uploads/'.$obj['filenewname'];
            }

        }

        return $result;
    }

    public function guardarCupcakesAction(Request $request)
    {
        $result = false;

        if($request->getMethod('post'))
        {
            $em             = $this->getDoctrine()->getManager();
            $id             = ($request->get('txt_id', false))? $request->get('txt_id'): 0;
            $txt_tipo       = ($request->get('txt_tipo', false))? $request->get('txt_tipo'): 0;
            $txt_texto_1    = ($request->get('txt_texto_1', false))? $request->get('txt_texto_1'): null;
            $txt_texto_2    = ($request->get('txt_texto_2', false))? $request->get('txt_texto_2'): null;
            $txt_precio     = ($request->get('txt_precio', false))? $request->get('txt_precio'): null;
            $foto           = ($request->files->get('foto', false))? $request->files->get('foto', false): null;

            if( !$cupcake = $em->getRepository('WebBundle:Cupcakes')->findOneBy(array('id' => $id )) )
            {
                $cupcake = new Cupcakes();
            }
                $cupcake->setTipo($txt_tipo);
                $cupcake->setTexto1($txt_texto_1);
                $cupcake->setTexto2($txt_texto_2);
                $cupcake->setPrecio($txt_precio);
                $cupcake->setActivo(1);

                if($img = $this->subirImagen($foto))
                {
                    $cupcake->setImagen($img);
                }

                $em->persist($cupcake);
                $em->flush();

                $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function guardarImagenBannerAction(Request $request)
    {
        $result = false;
        $foto   = ($request->files->get('foto', false))? $request->files->get('foto', false): null;
        $em     = $this->getDoctrine()->getManager();

        if($img = $this->subirImagen($foto))
        {
            $banner = new Banner();
            $banner->setImagen($img);
            $banner->setActivo(1);
            $em->persist($banner);
            $em->flush();

            $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function eliminarImagenBannerAction(Request $request)
    {
        $result = false;
        $id     = ($request->get('id', false))? $request->get('id'): 0;
        $em     = $this->getDoctrine()->getManager();

        if($banner = $em->getRepository('WebBundle:Banner')->findOneBy(array('id' => $id )))
        {
            $banner->setActivo(0);
            $em->persist($banner);
            $em->flush();

            $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function eliminarCupcakeAction(Request $request)
    {
        $result = false;
        $id     = ($request->get('id', false))? $request->get('id'): 0;
        $em     = $this->getDoctrine()->getManager();

        if($cupcake = $em->getRepository('WebBundle:Cupcakes')->findOneBy(array('id' => $id )))
        {
            $cupcake->setActivo(0);
            $em->persist($cupcake);
            $em->flush();

            $result = true;
        }

        echo json_encode(array('result' => $result));
        exit;
    }

    public function agregarCupcakeAction(Request $request)
    {
        $result         = false;
        $id             = ($request->get('id', false))? $request->get('id'): 0;
        $txt_cat        = ($request->get('txt_cat', false))? $request->get('txt_cat'): 0;
        $txt_texto_2    = ($request->get('txt_texto_2', false))? $request->get('txt_texto_2'): 0;
        $foto           = ($request->files->get('foto', false))? $request->files->get('foto', false): null;
        $em             = $this->getDoctrine()->getManager();

        if( $txt_texto_2 && $foto )
        {
            $fkCat = $em->getRepository('WebBundle:Categoria')->findOneBy(array('id' => $txt_cat));

            if(!$cupcake = $em->getRepository('WebBundle:Cupcakes')->findOneBy(array('id' => $id )))
            {
                $cupcake = new Cupcakes();
                $cupcake->setCategoria($fkCat);
            }
            $cupcake->setTipo(0);
            $cupcake->setTexto2($txt_texto_2);
            $cupcake->setActivo(1);

            if($img = $this->subirImagen($foto))
            {
                $cupcake->setImagen($img);
            }

            $em->persist($cupcake);
            $em->flush();

            $result = true;
            
        }

        echo json_encode(array('result' => $result));
        exit;
    }
}