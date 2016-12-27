<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WebBundle\Entity\Servicio;
use WebBundle\Entity\Producto;
use WebBundle\Entity\ProductoFoto;

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
                if($foto->getProducto())
                {
                    $lista_fotos[$foto->getProducto()->getId()][] = $foto->getFoto();
                }
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

    public function guardarProductoAction(Request $request)
    {
        $result = true;

        $texto = $request->get('texto', false);

        $em = $this->getDoctrine()->getManager();

        $producto = new Producto();
        $producto->setTitulo($texto);
        $producto->setActivo(1);
        $em->persist($producto);
        $em->flush();

        echo json_encode(array('result' => $result));
        exit;
    }

    public function guardarFotoProductoAction(Request $request)
    {
        $result = true;
        $foto = $this->subirImagen($request->files->get('foto', null));
        $id = $request->get('id', false);

        $em = $this->getDoctrine()->getManager();
        $producto = $em->getRepository('WebBundle:Producto')->findOneBy(array('id' => $id ));


        $proFoto = new ProductoFoto();
        $proFoto->setFoto($foto);
        $proFoto->setProducto($producto);
        $proFoto->setActivo(1);
        $em->persist($proFoto);
        $em->flush();

        echo json_encode(array('result' => $result));
        exit;
    }

    public function eliminaProductoAction(Request $request)
    {
        $result = true;

        $id = $request->get('id', false);
        $em = $this->getDoctrine()->getManager();

        $producto = $em->getRepository('WebBundle:Producto')->findOneBy(array('id' => $id ));
        $producto->setActivo(0);
        $em->persist($producto);
        $em->flush();

        echo json_encode(array('result' => $result));
        exit;
    }

    public function enviarContactoAction(Request $request)
    {
        $result = true;

        $datos_mail = array(
            'nombre'        => ucwords($request->get('nombre')),
            'correo'        => $request->get('correo'),
            'telefono'      => $request->get('telefono'),
            'mensaje'       => $request->get('mensaje'),
            'from'          => 'jonathanpadilla09@outlook.com',
            'to'            => 'jonathanpadilla0109@gmail.com',
        );

        $this->enviarMail($datos_mail);

        echo json_encode(array('result' => $result));
        exit;
    }

    private function enviarMail($arr = false)
    {
        // print_r($productos);
        $return = false;
        if(is_array($arr))
        {
            $headers = "From: ".$arr['from']."\r\n";
            $headers .= "Reply-To: ".$arr['to']."\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $contenido = $this->renderView('WebBundle::plantilla_contacto.html.twig', array('datos' => $arr));
            // echo $contenido; exit;
            if(mail($arr['to'], 'www.greenwalk.cl - '.$arr['nombre'], $contenido, $headers))
            {
                $return = $contenido;
            }
        }
        return $return;
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