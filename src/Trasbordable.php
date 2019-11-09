<?php
namespace TrabajoTarjeta;

class Trasbordable extends Pagable
{ 
    
    
    
    protected $Ultimotrasbordo = 1;
    
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
      * Funcion para ver si dispone del trasbordo.
      *
      * @param string $linea
      *   La linea de colectivo en la que se esta pagando, se utiliza para ver si hizo trasbordo.
      *
      * @param float $ValorBoleto
      *   El valor del boleto al que se realiza un 33%.
      *
      * @return float
      *   Si fue posible realizar la carga.
      */
     protected function puedeTrasbordo($linea, $ValorBoleto)
     {
         if ($this->UltimoColectivo == $linea || $this->UltimoValorPagado == 0.0 || $this->Ultimotrasbordo) {
             $this->Ultimotrasbordo = 0;
             return $ValorBoleto;
         }
         if ($this->dependeHora()) {
             if (($this->tiempo->time() - $this->UltimaHora) < 3600) {
                 $this->Ultimotrasbordo = 1;
                 return ($ValorBoleto * 0.33);
             }
         } else {
             if (($this->tiempo->time() - $this->UltimaHora) < 5400) {
                 $this->Ultimotrasbordo = 1;
                 return ($ValorBoleto * 0.33);
             }
         }
         $this->Ultimotrasbordo = 0;
         return $ValorBoleto;
     }
 
     /**
      * Dependiendo de la hora y el dia que sea puede haber un maximo de tiempo de 60 o 90 minutos.
      *
      * @return bool
      *   True si son 60 o false si son 90.
      */
     protected function dependeHora()
     {
         if ($this->tiempo->esFeriado() || date('N', $this->tiempo->time()) == 7){
             return false;
         }
         if (date('N', $this->tiempo->time()) == 6){
             if (date('G', $this->tiempo->time()) > 6 && date('G', $this->tiempo->time()) < 14){
                 return true;
             } else {
                 return false;
             }
         } else {
             if (date('G', $this->tiempo->time()) > 6 && date('G', $this->tiempo->time()) < 22){
                 return true;
             } else {
                 return false;
             }
         }
     }
 
     //lo anterior era clase transbordo
}