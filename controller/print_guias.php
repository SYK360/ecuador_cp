<?php

/**
 * Created by PhpStorm.
 * User: KSYK
 * Date: 19/01/2017
 * Time: 23:08
 */

require_model('factura_cliente.php');
require_model('cliente.php');
class print_guias  extends fs_controller
{

    public $direccion;
public $dic_id;
public $cliente;

    public function __construct()
    {
        parent::__construct(__CLASS__, 'Imprimir guias', 'ventas', FALSE, FALSE);
    }

    protected function private_core()
    {
        $this->share_extensions();
        $this->direccion = FALSE;

        if( isset($_REQUEST['id']) )
        {
            $this->direccion = TRUE;
            $this->imprimir_guia();

        }

    }

    private function imprimir_guia() {

        $this->direccion = new factura_cliente();
        $this-> dic_id = $this->direccion->get($_GET['id']);
        
        $cliente = new cliente();
        $this->cliente = $cliente->get($this->dic_id->codcliente);


    }


    private function share_extensions()
    {
        $extension = array(
            'name' => 'modal_print_guias',
            'page_from' => __CLASS__,
            'page_to' => 'ventas_factura',
            'type' => 'button',
            'text' => '<span class="glyphicon glyphicon-paste"></span> &nbsp; Imprimir Guias',
            'params' => ''
        );

        $fsext = new fs_extension($extension);
        $fsext->save();



    }



}