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
    public $fecha_anulada;
    public $hora_anulada;
    public $fecha_it_air;
    public $fecha_tt_air;
    public $hora_sa_air;
    public $hora_lle_air;
    public $fecha_emi_air;
    public $motivo_tr_air;
    public $placa_ve_air;
    public $dtototal;

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
            $this->fecha_anulada = $a['fecha_anulada'];
            $this->hora_anulada = $a['hora_anulada'];
            $this->fecha_it_air = $a['fecha_it_air'];
            $this->fecha_tt_air = $a['fecha_tt_air'];
            $this->hora_sa_air = $a['hora_sa_air'];
            $this->hora_lle_air = $a['hora_lle_air'];
            $this->fecha_emi_air = $a['fecha_emi_air'];
            $this->motivo_tr_air = $a['motivo_tr_air'];
            $this->placa_ve_air = $a['placa_ve_air'];
            $this->dtototal = $a['dtototal'];
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
            $this->fecha_anulada = NULL;
            $this->hora_anulada = NULL;
            $this->fecha_anulada = NULL;
            $this->hora_anulada =  NULL;
            $this->fecha_it_air = NULL;
            $this->fecha_tt_air = NULL;
            $this->hora_sa_air = NULL;
            $this->hora_lle_air = NULL;
            $this->fecha_emi_air = NULL;
            $this->motivo_tr_air = NULL;
            $this->placa_ve_air = NULL;
            $this->dtototal = 0;
        }

}


   public function get_lineas_iva()
   {
      $linea_iva = new \linea_iva_factura_cliente();
      $lineasi = $linea_iva->all_from_factura($this->idfactura);
      /// si no hay lineas de IVA las generamos
      if( !$lineasi )
      {
         $lineas = $this->get_lineas();
         if($lineas)
         {
            foreach($lineas as $l)
            {
               $i = 0;
               $encontrada = FALSE;
               while($i < count($lineasi))
               {
                  if($l->iva == $lineasi[$i]->iva AND $l->recargo == $lineasi[$i]->recargo)
                  {
                     $encontrada = TRUE;
                     $lineasi[$i]->neto += $l->pvptotal;
                     //TRABAJANDO..
                     $lineasi[$i]->dtototal += $l->dtolineal;
                     $lineasi[$i]->totaliva += ($l->pvptotal*$l->iva)/100;
                     $lineasi[$i]->totalrecargo += ($l->pvptotal*$l->recargo)/100;
                  }
                  $i++;
               }
               if( !$encontrada )
               {
                  $lineasi[$i] = new \linea_iva_factura_cliente();
                  $lineasi[$i]->idfactura = $this->idfactura;
                  $lineasi[$i]->codimpuesto = $l->codimpuesto;
                  $lineasi[$i]->iva = $l->iva;
                  $lineasi[$i]->recargo = $l->recargo;
                  $lineasi[$i]->neto = $l->pvptotal;
                  $lineasi[$i]->dtototal = $l->dtolineal;
                  $lineasi[$i]->totaliva = ($l->pvptotal*$l->iva)/100;
                  $lineasi[$i]->totalrecargo = ($l->pvptotal*$l->recargo)/100;
               }
            }

            /// redondeamos y guardamos
            if( count($lineasi) == 1 )
            {
               $lineasi[0]->neto = round($lineasi[0]->neto, FS_NF0);
               $lineasi[0]->dtototal = round($lineasi[0]->dtototal, FS_NF0);
               $lineasi[0]->totaliva = round($lineasi[0]->totaliva, FS_NF0);
               $lineasi[0]->totaliva = round($lineasi[0]->totaliva, FS_NF0);
               $lineasi[0]->totallinea = $lineasi[0]->neto + $lineasi[0]->totaliva + $lineasi[0]->totalrecargo;
               $lineasi[0]->save();
            }
            else
            {
               /*
                * Como el neto y el iva se redondean en la factura, al dividirlo
                * en líneas de iva podemos encontrarnos con un descuadre que
                * hay que calcular y solucionar.
                */
               $t_neto = 0;
               $t_iva = 0;
               foreach($lineasi as $li)
               {
                  $li->neto = bround($li->neto, FS_NF0);
                  $li->totaliva = bround($li->totaliva, FS_NF0);
                  $li->totallinea = $li->neto + $li->totaliva;

                  $t_neto += $li->neto;
                  $t_iva += $li->totaliva;
               }

               if( !$this->floatcmp($this->neto, $t_neto) )
               {
                  /*
                   * Sumamos o restamos un céntimo a los netos más altos
                   * hasta que desaparezca el descuadre
                   */
                  $diferencia = round( ($this->neto-$t_neto) * 100 );
                  usort($lineasi, function($a, $b) {
                     if($a->totallinea == $b->totallinea)
                     {
                        return 0;
                     }
                     else if($this->total < 0)
                     {
                        return ($a->totallinea < $b->totallinea) ? -1 : 1;
                     }
                     else
                     {
                        return ($a->totallinea < $b->totallinea) ? 1 : -1;
                     }
                  });

                  foreach($lineasi as $i => $value)
                  {
                     if($diferencia > 0)
                     {
                        $lineasi[$i]->neto += .01;
                        $diferencia--;
                     }
                     else if($diferencia < 0)
                     {
                        $lineasi[$i]->neto -= .01;
                        $diferencia++;
                     }
                     else
                        break;
                  }
               }

               if( !$this->floatcmp($this->totaliva, $t_iva) )
               {
                  /*
                   * Sumamos o restamos un céntimo a los importes más altos
                   * hasta que desaparezca el descuadre
                   */
                  $diferencia = round( ($this->totaliva-$t_iva) * 100 );
                  usort($lineasi, function($a, $b) {
                     if($a->totaliva == $b->totaliva)
                     {
                        return 0;
                     }
                     else if($this->total < 0)
                     {
                        return ($a->totaliva < $b->totaliva) ? -1 : 1;
                     }
                     else
                     {
                        return ($a->totaliva < $b->totaliva) ? 1 : -1;
                     }
                  });

                  foreach($lineasi as $i => $value)
                  {
                     if($diferencia > 0)
                     {
                        $lineasi[$i]->totaliva += .01;
                        $diferencia--;
                     }
                     else if($diferencia < 0)
                     {
                        $lineasi[$i]->totaliva -= .01;
                        $diferencia++;
                     }
                     else
                        break;
                  }
               }

               foreach($lineasi as $i => $value)
               {
                  $lineasi[$i]->totallinea = $value->neto + $value->totaliva + $value->totalrecargo;
                  $lineasi[$i]->save();
               }
            }
         }
      }
      return $lineasi;
   }

   

    public function save()
    {
        if( parent::save() )
        {
            $sql = "UPDATE ".$this->table_name." SET rent_iva_por = ".$this->var2str($this->rent_iva_por)
                .", rent_fuente_por = ".$this->var2str($this->rent_fuente_por)
                .", rent_iva = ".$this->var2str($this->rent_iva)
                .", rent_fuente = ".$this->var2str($this->rent_fuente)
                .", no_doc = ".$this->var2str($this->no_doc)
                .", no_aut = ".$this->var2str($this->no_aut)
                .", no_serie = ".$this->var2str($this->no_serie)
                .", fecha_anulada = ".$this->var2str($this->fecha_anulada)
                .", hora_anulada = ".$this->var2str($this->hora_anulada)
                .", fecha_it_air = ".$this->var2str($this->fecha_it_air)
                .", fecha_tt_air = ".$this->var2str($this->fecha_tt_air)
                .", hora_sa_air = ".$this->var2str($this->hora_sa_air)
                .", hora_lle_air = ".$this->var2str($this->hora_lle_air)
                .", fecha_emi_air = ".$this->var2str($this->fecha_emi_air)
                .", motivo_tr_air = ".$this->var2str($this->motivo_tr_air)
                .", placa_ve_air = ".$this->var2str($this->placa_ve_air)
                .", dtototal = ".$this->var2str($this->dtototal)                    
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
