<?php
namespace TrabajoTarjeta;

class Recargable 
{
    
    //protected $saldo = 0;
    
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
    
    public function Rrecargar($monto)
    {
        //$monto_tarjeta = $this->obtenerSaldo();

        switch ($monto) { //Diferentes montos a recargar
            case 10:
                //$this->cambiarSaldo($monto_tarjeta + 10);
                $monto= 10;
                break;
            case 20:
                $monto= 20;
                break;
            case 30:
                $monto= 30;
                break;
            case 50:
                 $monto= 50;
                break;
            case 100:
                $monto= 100;
                break;
            case 510.15:
                $monto= 592.08;
                break;
            case 962.59:
                $monto= 1184.17;
                break;
            default:
                //Devuelve false si el monto ingresado no es válido
                return 0;
        }
        return $monto;

    }
}
