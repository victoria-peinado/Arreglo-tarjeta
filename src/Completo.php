<?php
namespace TrabajoTarjeta;

/*/
Tarjeta completo
/*/
class Completo extends Tarjeta 
{
    public $ValorBoleto = VariablesConstantes::precioLibre; //El boleto vale 0// ANTES ERA PROTECTED

    /**
     * Devuelve el valor de boleto siendo este 0 para la franquicia completa.
     *
     * @param string $linea
     *   La linea de colectivo que no es usada aqui pero si en la clase que extiende.
     *
     * @return int
     *   El valor del boleto.
     */
    public function calculaValor($linea)
    {
        return $this->ValorBoleto; //Devuelve el valor ya almacenado
    }
}
