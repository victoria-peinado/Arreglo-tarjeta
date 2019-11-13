<?php

namespace TrabajoTarjeta;

interface VisorInterface
{

    /**
     * Devuelve el saldo disponible en la tarjeta.
     *
     * @return int
     */
    public function obtenerSaldo();

}
