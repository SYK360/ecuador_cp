<?php
/*
 * This file is part of FacturaScripts
 * Copyright (C) 2013-2016  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'plugins/facturacion_base/model/core/factura_cliente.php';

/**
 * Created by PhpStorm.
 * User: SYK
 * Date: 19/02/2017
 * Time: 22:03
 */

class factura_cliente extends FacturaScripts\model\factura_cliente
{

    public $rent_iva_por;
    public $rent_fuente_por;
    public $rent_iva;
    public $rent_fuente;
    public $no_doc;
    public $no_aut;
    public $no_serie;


    public function __construct($a = FALSE){

        parent::__construct($a);
        if($a)
        {
            $this->rent_iva_por = $a['rent_iva_por'];
            $this->rent_fuente_por = $a['rent_fuente_por'];
            $this->rent_iva = $a['rent_iva'];
            $this->rent_fuente = $a['rent_fuente'];
            $this->no_doc = $a['no_doc'];
            $this->no_aut = $a['no_aut'];
            $this->no_serie = $a['no_serie'];
        }
        else
        {
            $this->rent_iva_por = NULL;
            $this->rent_fuente_por = NULL;
            $this->rent_iva = NULL;
            $this->rent_fuente = NULL;
            $this->no_doc = NULL;
            $this->no_aut = NULL;
            $this->no_serie = NULL;
        }

}


    public function save()
    {
        if( parent::save() )
        {
            $sql = "UPDATE ".$this->table_name." SET rent_iva_por = ".$this->var2str($this->rent_iva_por)
                .", rent_fuente_por = ".$this->var2str($this->rent_fuente_por)
                .", rent_iva = ".$this->var2str($this->rent_iva)
                .", rent_fuente = ".$this->var2str($this->rent_fuente)
                ."  WHERE idfactura = ".$this->var2str($this->idfactura).";";

            return $this->db->exec($sql);
        }
        else
        {
            return FALSE;
        }
    }

   
    public function new_codigo() 
	{

 /// buscamos el nÃºmero inicial para la serie
      $num = 1;
      $serie0 = new \serie();
      $serie = $serie0->get($this->codserie);
      if($serie)
      {
        
            $num = $serie->numfactura;
         
      }
      
      
         
      /// buscamos un hueco o el siguiente nÃºmero disponible
      $encontrado = FALSE;
      $fecha = $this->fecha;
      $hora = $this->hora;
      $sql = "SELECT ".$this->db->sql_to_int('numero')." as numero,fecha,hora FROM ".$this->table_name
              ." WHERE codserie = ".$this->var2str($this->codserie)
              ." ORDER BY numero ASC;";
      
      $data = $this->db->select($sql);
      if($data)
      {
         foreach($data as $d)
         {
            if( intval($d['numero']) < $num )
            {
               /**
                * El nÃºmero de la factura es menor que el inicial.
                * El usuario ha cambiado el nÃºmero inicial despuÃ©s de hacer
                * facturas.
                */
            }
            else if( intval($d['numero']) == $num )
            {
               /// el nÃºmero es correcto, avanzamos
               $num++;
            }
            else
            {
               /// Hemos encontrado un hueco y debemos usar el nÃºmero y la fecha.
               $encontrado = TRUE;
               $fecha = Date('d-m-Y', strtotime($d['fecha']));
               $hora = Date('H:i:s', strtotime($d['hora']));
               break;
            }
         }
      }
         if($encontrado)
      {
         $this->numero = $num;
         $this->fecha = $fecha;
         $this->hora = $hora;
      }
      else
      {
         $this->numero = $num;         
   
      }
     
         $this->codigo = $this->codserie.sprintf('%07s', $this->numero);
      
    }

}
