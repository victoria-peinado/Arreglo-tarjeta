<?php
namespace TrabajoTarjeta;

class Recargable extends Trasbordo
{
	protected $saldo = 0;
       /**
     * Funcion para recargar la tarjeta.
     *
     * @param float $monto
     *   Las cargas aceptadas de tarjetas son: (10, 20, 30, 50, 100, 510,15 y 962,59)
     *   Cuando se cargan $510,15 se acreditan de forma adicional: 81,93
     *   Cuando se cargan $962,59 se acreditan de forma adicional: 221,58
     *
     * @return bool
     *   Si fue posible realizar la carga.
     */
    public function recargar($monto)
    {

        switch ($monto) { //Diferentes montos a recargar
            case 10:
                $this->saldo += 10;
                break;
            case 20:
                $this->saldo += 20;
                break;
            case 30:
                $this->saldo += 30;
                break;
            case 50:
                $this->saldo += 50;
                break;
            case 100:
                $this->saldo += 100;
                break;
            case 510.15:
                $this->saldo += 592.08;
                break;
            case 962.59:
                $this->saldo += 1184.17;
                break;
            default:
                //Devuelve false si el monto ingresado no es válido
                return false;
        }
        //$this->pagarPlus(); //Ejecuta la funcion parta pagar plus en caso de que los deba//NO ES EL LUGAR CORRECTO PARA LLAMAR A ESTA FUNCION
        // Devuelve true si el monto ingresado es válido
        return true;
    }
}
