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
       $sql = "UPDATE ".$this->table_name." SET dtolineal =
       ".$this->var2str($this->dtolineal).",
       ivalineal = ".$this->var2str($this->ivalineal)."
       ;";
       return $this->db->exec($sql);
        }else{
            
            return FALSE;
        }
   }
    
}
