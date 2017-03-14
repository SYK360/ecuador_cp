<?php

/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2015-2017  Carlos Garcia Gomez  neorazorx@gmail.com
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

/**
 * Description of admin_ecuador
 *
 * @author carlos
 */
class admin_ecuador extends fs_controller
{
   public function __construct()
   {
      parent::__construct(__CLASS__, 'Ecuador', 'admin');
   }
   
   protected function private_core()
   {
      $this->share_extensions();
      
      if( isset($_GET['opcion']) )
      {
         if($_GET['opcion'] == 'moneda')
         {
            $this->empresa->coddivisa = 'USD';
            if( $this->empresa->save() )
            {
               $this->new_message('Datos guardados correctamente.');
            }
         }
         else if($_GET['opcion'] == 'pais')
         {
            $this->empresa->codpais = 'ECU';
            if( $this->empresa->save() )
            {
               $this->new_message('Datos guardados correctamente.');
            }
         }
      }
      else
      {
         $this->check_ejercicio();
      }
   }
   
   private function share_extensions()
   {
      $fsext = new fs_extension();
      $fsext->name = 'plan_ecuador';
      $fsext->from = __CLASS__;
      $fsext->to = 'contabilidad_ejercicio';
      $fsext->type = 'fuente';
      $fsext->text = 'Plan contable de Ecuador';
      $fsext->params = 'plugins/ecuador/extras/ecuador.xml';
      $fsext->save();
   }
   
   private function check_ejercicio()
   {
      $ej0 = new ejercicio();
      foreach($ej0->all_abiertos() as $ejercicio)
      {
         if($ejercicio->longsubcuenta != 6)
         {
            $ejercicio->longsubcuenta = 6;
            if( $ejercicio->save() )
            {
               $this->new_message('Datos del ejercicio '.$ejercicio->codejercicio.' modificados correctamente.');
            }
            else
            {
               $this->new_error_msg('Error al modificar el ejercicio.');
            }
         }
      }
   }
}
