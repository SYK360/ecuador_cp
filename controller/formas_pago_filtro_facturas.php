<?php


class formas_pago_filtro_facturas extends fs_controller
{

public $fpago;


 public function __construct()
    {
        parent::__construct(__CLASS__, 'Filtro Formas de Pago', 'ventas', FALSE, FALSE);
    }


}