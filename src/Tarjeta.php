<?php

namespace TrabajoTarjeta;

class Tarjeta extends Trasbordable  implements TarjetaInterface
{   public $saldo = 0;
    protected $id;
    protected $recargable;
    public $ValorBoleto = 14.8;
    public $plus = 0;
    public $pagoplus = 0;
    public $tiempo;
    public $UltimoValorPagado = null;
    public $UltimaHora = 0;
    public $UltimoColectivo;

    public function __construct($id, TiempoInterface $tiempo, $recargable)
    {
        $this->id = $id; //Guarda el ID
        $this->tiempo = $tiempo; //Guarda la variable tiempo la cual le es inyectada
        $this->recargable = $recargable;
        $this->pagable = $pagable;
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
    public function restarSaldo($linea)
    {
        return ($this->pagable->PrestarSaldo($linea,$this));
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


     /**
     * Para el caso de la tarjeta ejecuta una funcion que se fija si puede hacer trasbordo.
     *
     * @param string $linea
     *   La linea de colectivo en la que se esta pagando, se utiliza para ver si hizo trasbordo.
     *
     * @return float
     *   El valor del pasaje a pagar.
     */
     protected function calculaValor($linea)
     {
         return ($this->puedeTrasbordo($linea, $this->ValorBoleto));
     }

     /**
     * Devuelve el ultimo valor pagado.
     *
     * @return float
     */
    public function valorPagado()
     {
         return $this->UltimoValorPagado; // Devuelve el ultimo valor que se pago
     }
   
    /**
    * Devuelve la ultima hora en la que se uso la tarjeta.
    *
    * @return int
    */
    public function ultimaHoraUsada()
    {
      return $this->UltimaHora; // Devuelve la ultima hora a la que se pago
    }
        
    /**
    * Devuelve si se utilizo un viaje plus.
    *
     * @return int
    */
    public function usoPlus()
    {
         return $this->plus; // Devuelve si se utilizo un viaje plus
    }
    
    /**
    * Setea a 0 el "pago plus". Esta funcion se ejecutara cuando se emite el boleto.
    *
    * @return int
    *   La cantidad de plus que pago en la ultiima recarga.
    *ESTE NO ES EL LUGAR CORRECTO SE TIENE QUE HACER DESPUES DE EJECUTAR PAGAR PLUS
    */
    public function obtenerPagoPlus()
    {
        $pagoplusaux = $this->pagoplus; // Se almacena en un auxiliar
        $this->pagoplus = 0; // se Reinicia
        return $pagoplusaux; // Se devuelve el auxiliar
    }
    
}
    