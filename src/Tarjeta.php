<?php

namespace TrabajoTarjeta;

class Tarjeta extends Trasbordable  implements TarjetaInterface
{   protected $saldo = 0;
    protected $id;
    protected $recargable;


    public function __construct($id, TiempoInterface $tiempo, $recargable)
    {
        $this->id = $id; //Guarda el ID
        $this->tiempo = $tiempo; //Guarda la variable tiempo la cual le es inyectada
        $this->recargable = $recargable;
    }
    public function recargar ($monto)
    {
        if (($this->recargable->Rrecargar($monto)) == 0){
            return false;
        }
        $this->saldo += ($this->recargable->Rrecargar($monto));
        return true;
    }
    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtenerSaldo()
    {
        return $this->saldo;
    }
   
    public function cambiarSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    /**
     * Devuelve el valor completo del boleto.
     *
     * @return float
     */
    public function boletoCompleto()
    {
        return $this->ValorBoleto; // Devuelve el valor de un boleto completo
    }

    /**
     * Devuelve el ID de la tarjeta.
     *
     * @return int
     */
    public function obtenerId()
    {
        return $this->id; //Devuelve el id de la tarjeta
    }


   
}
