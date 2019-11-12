<?php

namespace TrabajoTarjeta;

class Boleto implements BoletoInterface
{


    protected $saldo; //Saldo restante


    public function __construct($tarjeta)
    {
        $this->saldo = $tarjeta->obtenerSaldo();
    }


    /**
     * Devuelve un numero que respresenta el saldo restante de la tarjeta.
     *
     * @return Float
     */
    public function obtenerSaldo()
    {
        return $this->saldo;
    }


    
}
