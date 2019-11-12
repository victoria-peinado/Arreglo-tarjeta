<?php

namespace TrabajoTarjeta;

class Boleto implements BoletoInterface
{

    protected $valor; //Valor pagado

    protected $colectivo; //Colectivo en el que se pago

    protected $tarjeta; //Tarjeta con la que se pago

    protected $cantplus; //Si se pagaron plus en la ultima recarga

    protected $hora; //hora de pago

    protected $idtarjeta; //id de la tarjeta

    protected $boletoCompleto; //Valor de un boleto completo de colectivo para el calculo del plus

    protected $linea; //Linea del colectivo

    protected $saldo; //Saldo restante

    protected $descripcion; //Descripcion del boleto plus

    protected $Tipo; //Tipo de tarjeta que se utilizo

    protected $usoPlus; //Si se utilizaron plus

    protected $PagoPlus;

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
