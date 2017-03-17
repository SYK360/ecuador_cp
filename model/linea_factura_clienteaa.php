<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'plugins/facturacion_base/model/core/linea_factura_cliente.php';

/**
 * Description of linea_factura_cliente
 *
 * @author SaykoLenovo
 */


class linea_factura_cliente extends FacturaScripts\model\linea_factura_cliente

{
   public $ivalineal;

     public function __construct($a = FALSE){

        parent::__construct($a);
        if($a)
        {

            $this->dtolineal = floatval($a['dtolineal']);
            $this->ivalineal = floatval($a['ivalineal']);
        }else{
            
          $this->dtototal = 0;
            $this->ivalineal = 0;
        }
            
        
        
        
   }


    //IVA 0%
    public function iva0(){
        if ($this->iva == 0) {
            return $this->pvptotal;
        }else{
            return 0;
        }
    }

    //DTO Total

    public function total_des()
    {
        return $this->pvptotal * (100 - $this->dtopor) / 100;
    }


   
   public function save()
   {
       if( parent::save() )
        {
            if( $this->exists() )

            {
                $sql = "UPDATE ".$this->table_name." SET idfactura = ".$this->var2str($this->idfactura)
                    .", idalbaran = ".$this->var2str($this->idalbaran)
                    .", idlineaalbaran = ".$this->var2str($this->idlineaalbaran)
                    .", referencia = ".$this->var2str($this->referencia)
                    .", descripcion = ".$this->var2str($this->descripcion)
                    .", cantidad = ".$this->var2str($this->cantidad)
                    .", pvpunitario = ".$this->var2str($this->pvpunitario)
                    //TRABAJANDO..
                    .", dtolineal = ".$this->var2str($this->dtolineal)
                    .", pvpsindto = ".$this->var2str($this->pvpsindto)
                    .", dtopor = ".$this->var2str($this->dtopor)
                    .", pvptotal = ".$this->var2str($this->pvptotal)
                    .", codimpuesto = ".$this->var2str($this->codimpuesto)
                    .", iva = ".$this->var2str($this->iva)
                    //WORKING...
                    .", ivalienal = ".$this->var2str($this->ivalineal)
                    .", recargo = ".$this->var2str($this->recargo)
                    .", irpf = ".$this->var2str($this->irpf)
                    .", orden = ".$this->var2str($this->orden)
                    .", mostrar_cantidad = ".$this->var2str($this->mostrar_cantidad)
                    .", mostrar_precio = ".$this->var2str($this->mostrar_precio)
                    ."  WHERE idlinea = ".$this->var2str($this->idlinea).";";

                return $this->db->exec($sql);
            }else{

                $sql = "INSERT INTO ".$this->table_name." (idfactura,idalbaran,idlineaalbaran,referencia,
               descripcion,cantidad,pvpunitario,pvpsindto,dtolineal,dtopor,pvptotal,codimpuesto,iva,ivalineal,
               recargo,irpf,orden,mostrar_cantidad,mostrar_precio) VALUES
                      (".$this->var2str($this->idfactura)
                    .",".$this->var2str($this->idalbaran)
                    .",".$this->var2str($this->idlineaalbaran)
                    .",".$this->var2str($this->referencia)
                    .",".$this->var2str($this->descripcion)
                    .",".$this->var2str($this->cantidad)
                    .",".$this->var2str($this->pvpunitario)
                    .",".$this->var2str($this->pvpsindto)
                    //TRABAJANDO
                    .",".$this->var2str($this->dtolineal)
                    .",".$this->var2str($this->dtopor)
                    .",".$this->var2str($this->pvptotal)
                    .",".$this->var2str($this->codimpuesto)
                    .",".$this->var2str($this->iva)
                    .",".$this->var2str($this->ivalineal)
                    .",".$this->var2str($this->recargo)
                    .",".$this->var2str($this->irpf)
                    .",".$this->var2str($this->orden)
                    .",".$this->var2str($this->mostrar_cantidad)
                    .",".$this->var2str($this->mostrar_precio).");";


                if( $this->db->exec($sql) )
                {
                    $this->idlinea = $this->db->lastval();
                    return TRUE;
                }
                else{
                    return FALSE;
            }

       }
   }else{
           return FALSE;
       }
   }
    
}
