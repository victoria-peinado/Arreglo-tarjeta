<?php

namespace TrabajoTarjeta;

interface TarjetaInterface
{

    /**
     * Recarga una tarjeta con un cierto valor de dinero.
     *
     * @param float $monto
     *
     * @return bool
     *   Devuelve TRUE si el monto a cargar es válido, o FALSE en caso de que no
     *   sea valido.
     */
	public function recargar($monto);//esta es una funcion que extiende de recargable

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtenerSaldo();

    public function restarSaldo($linea);// esto va a ser la clase pagar

}
