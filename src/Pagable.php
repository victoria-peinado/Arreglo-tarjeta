<?php
namespace TrabajoTarjeta;

class Pagable
{ 

    /**
     * Funcion para pagar plus en caso de deberlos.
     */
    protected function pagarPlus()
     {
         if ($tarjeta->plus == 2) { //Si debe 2 plus
             if ($tarjeta->saldo >= ($tarjeta->ValorBoleto * 2)) { //Y si le alcanza el saldo para pagarlos
                 $tarjeta->saldo -= ($tarjeta->ValorBoleto * 2); //Se le resta el valor
                 $tarjeta->plus = 0; //Se le devuelve los plus
                 $tarjeta->pagoplus = 2; //Se almacena que se pagaron 2 plus
             } else if ($tarjeta->saldo >= $tarjeta->ValorBoleto) { // Si solo alcanza para 1 plus
                 $tarjeta->saldo -= $tarjeta->ValorBoleto; //se le descuenta
                 $tarjeta->plus = 1; // Se lo devuelve
                 $tarjeta->pagoplus = 1; // Se indica que se pago un plus
             }
         } else {
             if ($tarjeta->plus == 1 && $tarjeta->saldo > $tarjeta->ValorBoleto) { //si debe 1 plus
                 $tarjeta->saldo -= $tarjeta->ValorBoleto; //Se le descuenta
                 $tarjeta->plus = 0; //Se le devuelve
                 $tarjeta->pagoplus = 1; // Se indica que se pago un plus
             }
         }
     }
   
 
     /**
      * Resta un boleto a la tarjeta.
      *
      * @param string $linea
      *   La linea de colectivo en la que se esta pagando, se utiliza para ver si hizo trasbordo.
      *
      * @return int
      *   Si fue posible realizar el pago.
      */
     public function PrestarSaldo($linea,$tarjeta)
     {	
         $this->pagarPlus();//ESTE SERIA EL LUGAR CORRECTO PARA RESTAR LOS PLUS
         if(($tarjeta instanceof Medio)&&(($tarjeta->tiempo->time() - $tarjeta->UltimaHora) < 299)){
             return false;
         }
         $ValorARestar = $tarjeta->calculaValor($linea); //Calcula el valor de el boleto
         if ($tarjeta->saldo >= $ValorARestar) { // Si hay saldo
             $tarjeta->saldo -= $ValorARestar; //Se le resta
             $tarjeta->UltimoValorPagado = $ValorARestar; //Se guarda cuanto pago
             $tarjeta->UltimoColectivo = $linea;
             $tarjeta->UltimaHora = $tarjeta->tiempo->time(); //Se guarda la hora de la transaccion // ESTA LINEA NO LA ENTIENDO
             return true; //Se finaliza la funcion
         }
         if ($tarjeta->plus < 2) { //Si tiene plus disponibles
             $tarjeta->plus++; // Se le resta
             $tarjeta->UltimoValorPagado = 0.0; //Se indica que se pago 0.0
             $tarjeta->UltimoColectivo = $linea;
             $tarjeta->UltimaHora = $tarjeta->tiempo->time(); //Se almacena la hora de la transaccion
             return true; // Se finaliza
         }
         return false; // No fue posible pagar
     }
 
    

   
}