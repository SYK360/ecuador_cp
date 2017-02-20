<?php

/**
 * Created by PhpStorm.
 * User: SYK
 * Date: 19/02/2017
 * Time: 21:53
 */

require_model('factura_proveedor.php');

class factura_prov_rent extends fs_controller
{

    public $factura;
    public $calcu_rent_iva;
    public $calcu_rent_fuente;


    public function __construct()
    {
        parent::__construct(__CLASS__, 'Factura de compras', 'compras', FALSE,  FALSE);
    }

    protected function private_core()
    {

        $this->share_extensions();
        $this->factura = FALSE;

        if( isset($_REQUEST['id']) )
        {
            $fc = new factura_proveedor();
            $this->factura = $fc->get($_REQUEST['id']);

        }

        if($this->factura)
        {
            if( isset($_POST['rent_iva_por']) )
            {
                $this->calcular_rent();
                $this->factura->rent_iva_por = $_POST['rent_iva_por'];
                $this->factura->rent_fuente_por = $_POST['rent_fuente_por'];



                if( $this->factura->save() )
                {
                    $this->new_message('Datos guardados correctamente.');
                }
                else
                {
                    $this->new_error_msg('Error al guardar los datos.');
                }
            }
        }
        else
        {
            $this->new_error_msg('Factura no encontrada.', 'error', FALSE, FALSE);
        }


    }



}