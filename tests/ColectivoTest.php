<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase
{

    /**
     * Probamos la creacion del colectivo y la realizacion de un pago
     */
    public function testPagarColectivo()
    {
        $tiempo = new Tiempo;
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
		$saldoEsperado =100;
        $colectivo = new Colectivo(122, "Semtur", 37);
        /*
        Probamos la asignacion de parametros iniciales
         */
        $this->assertEquals($colectivo->linea(), 122);
        $this->assertEquals($colectivo->empresa(), "Semtur");
        $this->assertEquals($colectivo->numero(), 37);
        /*
        Probamos la realizacion de una viaje
         */
        $this->assertEquals($colectivo->pagarCon($tarjeta), new Boleto($tarjeta));
		$saldoEsperado =$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
    }

    /**
     * Probamos la realizacion de un pago sin saldo y el uso de plus
     */
    public function testSinSaldo()
    {

        $tiempo = new Tiempo;
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $colectivo = new Colectivo(141, "Semtur", 37);
        /*
        Probamos la realizacion de una viaje sin saldo
         */
        $this->assertEquals($colectivo->pagarCon($tarjeta), new Boleto($tarjeta));
        $this->assertEquals($colectivo->pagarCon($tarjeta), new Boleto($tarjeta));
        $this->assertEquals($colectivo->pagarCon($tarjeta), false);
    }
}
