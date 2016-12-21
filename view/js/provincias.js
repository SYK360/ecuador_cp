/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2015-2016  Carlos Garcia Gomez  neorazorx@gmail.com
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

var provincia_list = [
   {value: 'Azuay'},
   {value: 'Bolívar'},
   {value: 'Cañar'},
   {value: 'Carchi'},
   {value: 'Chimborazo'},
   {value: 'Cotopaxi'},
   {value: 'El Oro'},
   {value: 'Esmeraldas'},
   {value: 'Galápagos'},
   {value: 'Guayas'},
   {value: 'Imbabura'},
   {value: 'Loja'},
   {value: 'Los Ríos'},
   {value: 'Manabí'},
   {value: 'Morona Santiago'},
   {value: 'Napo'},
   {value: 'Orellana'},
   {value: 'Pastaza'},
   {value: 'Pichincha'},
   {value: 'Santa Elena'},
   {value: 'Santo Domingo de los Tsáchilas'},
   {value: 'Sucumbíos'},
   {value: 'Tungurahua'},
   {value: 'Zamora Chinchipe'},   
];

$(document).ready(function() {
   $("#ac_provincia, #ac_provincia2").autocomplete({
      lookup: provincia_list,
   });
});
