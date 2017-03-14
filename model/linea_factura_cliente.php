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
   
     public function __construct($a = FALSE){

        parent::__construct($a);
        if($a)
        {
            
         $this->dtolineal = floatval($a['dtolineal']);
         
        }else{
            
          $this->dtototal = 0;
            
        }
            
        
        
        
   }
   
   public function save()
   {
       if( parent::save() )
        {
       $sql = "UPDATE ".$this->table_name." SET dtolineal = ".$this->var2str($this->dtolineal).";";
       return $this->db->exec($sql);
        }else{
            
            return FALSE;
        }
   }
    
}
