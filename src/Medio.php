<?php
namespace TrabajoTarjeta;

/*/
Tarjeta medio
/*/
class Medio extends Tarjeta
{

    public $UltimaHora = -300; //Para poder usarlo apenas se compra//ANTES ERA PROTECTED

    /**
     * Resta el saldo a la tarjeta, pero con una limitacion de no poder pagar un boleto si pasaron menos de 5 minutos.
     *
     * @param string $linea
     *   La linea en la que esta intentando pagar.
     *
     * @return bool
     *   Si se pudo realizar el pago. 
     * NO ES CORRECTO QUE EXTIENDA TARJETA SI DESPUES VA A SOBRE ESCRIBIR EL METODO
     */


    /**
     * Devuelve el valor del boleto a pagar, pero antes se fija si puede hacer un trasbordo utilizando al otra funcion.
     *
     * @param string $linea
     *   La linea en la que esta intentando pagar.
     *
     * @return float
     *   El valor del boleto a pagar.
     */
    public function calculaValor($linea)
    {
        return ($this->puedeTrasbordo($linea, ($this->ValorBoleto / 2)));
    }
}
