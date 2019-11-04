<?php
namespace TrabajoTarjeta;

class Pagable extends Recargable
{ 
    protected $ValorBoleto = 14.8;
    protected $plus = 0;
    protected $pagoplus = 0;
    protected $tiempo;
    protected $UltimoValorPagado = null;
    protected $UltimaHora = 0;
    protected $UltimoColectivo;
    /**
     * Funcion para pagar plus en caso de deberlos.
     */
     protected function pagarPlus()
     {
         if ($this->plus == 2) { //Si debe 2 plus
             if ($this->saldo >= ($this->ValorBoleto * 2)) { //Y si le alcanza el saldo para pagarlos
                 $this->saldo -= ($this->ValorBoleto * 2); //Se le resta el valor
                 $this->plus = 0; //Se le devuelve los plus
                 $this->pagoplus = 2; //Se almacena que se pagaron 2 plus
             } else if ($this->saldo >= $this->ValorBoleto) { // Si solo alcanza para 1 plus
                 $this->saldo -= $this->ValorBoleto; //se le descuenta
                 $this->plus = 1; // Se lo devuelve
                 $this->pagoplus = 1; // Se indica que se pago un plus
             }
         } else {
             if ($this->plus == 1 && $this->saldo > $this->ValorBoleto) { //si debe 1 plus
                 $this->saldo -= $this->ValorBoleto; //Se le descuenta
                 $this->plus = 0; //Se le devuelve
                 $this->pagoplus = 1; // Se indica que se pago un plus
             }
         }
     }
   
 
     /**
      * Resta un boleto a la tarjeta.
      *
      * @param string $linea
      *   La linea de colectivo en la que se esta pagando, se utiliza para ver si hizo trasbordo.
      *
      * @return bool
      *   Si fue posible realizar el pago.
      */
     public function restarSaldo($linea)
     {	
         $this->pagarPlus();//ESTE SERIA EL LUGAR CORRECTO PARA RESTAR LOS PLUS
         $ValorARestar = $this->calculaValor($linea); //Calcula el valor de el boleto
         if ($this->saldo >= $ValorARestar) { // Si hay saldo
             $this->saldo -= $ValorARestar; //Se le resta
             $this->UltimoValorPagado = $ValorARestar; //Se guarda cuanto pago
             $this->UltimoColectivo = $linea;
             $this->UltimaHora = $this->tiempo->time(); //Se guarda la hora de la transaccion
             return true; //Se finaliza la funcion
         }
         if ($this->plus < 2) { //Si tiene plus disponibles
             $this->plus++; // Se le resta
             $this->UltimoValorPagado = 0.0; //Se indica que se pago 0.0
             $this->UltimoColectivo = $linea;
             $this->UltimaHora = $this->tiempo->time(); //Se almacena la hora de la transaccion
             return true; // Se finaliza
         }
         return false; // No fue posible pagar
     }
 
      //todo lo antarior iria en la clase pagar
}