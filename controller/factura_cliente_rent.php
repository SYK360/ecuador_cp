<?php

/**
 * Created by PhpStorm.
 * User: KSYK
 * Date: 15/01/2017
 * Time: 1:14
 */

require_model('factura_cliente.php');
require_model('articulo.php');


class factura_cliente_rent extends  fs_controller
{

    public $factura;
    public $calcu_rent_iva;
    public $calcu_rent_fuente;


    public function __construct()
    {
        parent::__construct(__CLASS__, 'Factura de cliente', 'ventas', FALSE,  FALSE);
    }

    protected function private_core()
    {
        $this->share_extensions();
        $this->factura = FALSE;
        if( isset($_REQUEST['id']) )
        {
            $fc = new factura_cliente();
            $this->factura = $fc->get($_REQUEST['id']);

        }

        if( isset($_GET['anular']) )
        {
            $this->anular_factura();
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

    private function anular_factura()
    {
        $fc = new factura_cliente();
        $this->factura = $fc->get($_GET['anular']);

        if($this->factura)
        {

        /// Ponemos valores en ceros de la factura para informes
        $this->factura->anulada = TRUE;
        $this->factura->rent_iva = NULL;
        $this->factura->rent_fuente = NULL;
        $this->factura->rent_fuente_por = 0.00;
        $this->factura->rent_iva_por = 0.00;
        $this->factura->totaliva = 0.00;
        $this->factura->totaleuros = 0.00;
        $this->factura->neto =  0.00 ;
        $this->factura->total =  0.00 ;
        $this->factura->save();


         $linea_iva0 = new linea_iva_factura_cliente();

         foreach($this->factura->get_lineas_iva() as $linea_iva)
         {

            if($linea_iva0){

                $linea_iva->iva = 0 ;
                $linea_iva->neto = 0 ;
                $linea_iva->recargo = 0;
                $linea_iva->totaliva = 0;
                $linea_iva->totallinea = 0;
                $linea_iva->totalrecargo = 0;
                $linea_iva->save();

            }


         }


        /// Restauramos el stock
        $art0 = new articulo();


        foreach($this->factura->get_lineas() as $linea)

        {
            if( is_null($linea->idalbaran) )
            {
                $articulo = $art0->get($linea->referencia);



                if($articulo)
                {

                    $articulo->sum_stock($this->factura->codalmacen, $linea->cantidad);


                    $linea->cantidad = 0;
                    $linea->pvpsindto = 0.00;
                    $linea->pvpunitario = 0.00;
                    $linea->pvptotal = 0.00;
                    $linea->save();

                }
            }
        }

        $this->new_message("Factura de venta ".$this->factura->codigo." anulada correctamente.", TRUE);
        $this->clean_last_changes();
        header('Location:index.php?page=ventas_facturas');

        /* else
            $this->new_error_msg("¡Imposible eliminar la factura!");*/
    }else
        $this->new_error_msg("Factura no encontrada.");


}


    public function calcular_rent()

    {

        $this->calcu_rent_iva = $this->factura->totaliva  * $this->factura->rent_iva_por = $_POST['rent_iva_por'] / 100;
        $this->factura->rent_iva = round ($this->calcu_rent_iva, FS_NF0);
        //Redondeamos e ingresamos el monto retenido del iva
        $this->calcu_rent_fuente = $this->factura->neto  * $this->factura->rent_fuente_por = $_POST['rent_fuente_por'] / 100;
        //Redondeamos e ingresamos el monto retenido de la fuente
        $this->factura->rent_fuente = round($this->calcu_rent_fuente, FS_NF0);


    }

    private function share_extensions()
    {
        $fsext = new fs_extension();
        $fsext->name = 'tab_collapse_factura_cliente_rent';
        $fsext->from = __CLASS__;
        $fsext->to = 'ventas_factura';
        $fsext->type = 'tab';
        $fsext->text = '<span class="glyphicon glyphicon-paste"></span> &nbsp; Retención';
        $fsext->save();


    }

    public function url()
    {
        if($this->factura)
        {
            return parent::url().'&id='.$this->factura->idfactura;
        }
        else
        {
            return parent::url();
        }
    }

}