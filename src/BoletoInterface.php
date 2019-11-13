<?php

namespace TrabajoTarjeta;

interface BoletoInterface
{

    /**
     * Devuelve el saldo disponible en la tarjeta.
     *
     * @return int
     */
    public function obtenerSaldo();

}
