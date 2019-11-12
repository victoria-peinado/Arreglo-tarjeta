<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase
{

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo()
    {
        $tiempo = new Tiempo();
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);

        $this->assertTrue($tarjeta->recargar(10));
        $this->assertEquals($tarjeta->obtenerSaldo(), 10);

        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 30);

        $this->assertTrue($tarjeta->recargar(1119.90));
        $this->assertEquals($tarjeta->obtenerSaldo(), 1330);

        $this->assertTrue($tarjeta->recargar(2114.11));
        $this->assertEquals($tarjeta->obtenerSaldo(), 3930);

        $this->assertTrue($tarjeta->recargar(30));
        $this->assertEquals($tarjeta->obtenerSaldo(), 3960);

        $this->assertTrue($tarjeta->recargar(50));
        $this->assertEquals($tarjeta->obtenerSaldo(), 4010);

        $this->assertTrue($tarjeta->recargar(100));
        $this->assertEquals($tarjeta->obtenerSaldo(), 4110);
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido()
    {
        $tiempo = new Tiempo();
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);

        $this->assertFalse($tarjeta->recargar(15));
        $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    }
    /*
     * Comprueba que la tarjeta tiene viajes plus
     */
    public function testViajesPlus()
    {
        $tiempo = new Tiempo();
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);

        $this->assertTrue($tarjeta->recargar(50));
        $this->assertEquals($tarjeta->obtenerSaldo(), 50);

        $this->assertEquals($tarjeta->restarSaldo("153"), true);
        $this->assertEquals($tarjeta->obtenerSaldo(), 17.5);

        $this->assertEquals($tarjeta->restarSaldo("153"), true);
        $this->assertEquals($tarjeta->restarSaldo("153"), true);
        $this->assertEquals($tarjeta->restarSaldo("153"), false);
    }

    /*
     * Comprueba que se puede recargargar el viaje plus despues de restar saldo
     */
    public function testRecargarPlus()
    {
        $tiempo = new Tiempo;
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);

        $this->assertTrue($tarjeta->recargar(50));//saldo 20
        $this->assertEquals($tarjeta->restarSaldo("153"), true);//-14.8
        $this->assertEquals($tarjeta->restarSaldo("153"), true);//debe un plus
        $this->assertEquals($tarjeta->obtenerSaldo(), 17.5);
        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 37.5);//no es 0.4 porque resto el plus cuando pago un voleto
        $this->assertEquals($tarjeta->restarSaldo("153"), true);
        $this->assertEquals($tarjeta->restarSaldo("153"), true);
        $this->assertEquals($tarjeta->obtenerSaldo(), 5);
        $this->assertEquals($tarjeta->restarSaldo("153"), false);
    }

    /*
    Pruebo muchas cosas de trasbordo, con respecto al funcionamiento con el tiempo
     */
    public function testTrasbordo()
    {
        $tiempo = new TiempoFalso(0);
        $tiempo->agregarFeriado("01-06");
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tiempo->avanzar(28800);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);

        //Pruebo pagar un trasbordo un dia feriado con 90 minutos de espera y el texto del boleto
        $boleto = $colectivo1->pagarCon($tarjeta);
        $this->assertEquals(date('N', $tiempo->time()), '4');
        $this->assertEquals(date('G', $tiempo->time()), '8');
        $this->assertEquals(date('d-m', $tiempo->time()), "01-01");
        $this->assertEquals($boleto->obtenerFecha(), "01/01/1970 08:00:00");
        $this->assertEquals($tarjeta->obtenerSaldo(), 167.5);
        $tiempo->avanzar(4200);
        $boleto2 = $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($boleto2->obtenerDescripcion(), "Trasbordo Normal 10.725");
        $this->assertEquals($tarjeta->obtenerSaldo(), 156.775);

        //Pruebo pagar un trasbordo en un mismo colectivo
        $tiempo->avanzar(7200);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 124.275);
        $tiempo->avanzar(2300);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 91.775);

        //Pruebo pagar un trasbordo un dia feriado cuando ya pasaron los 90 minutos
        $tiempo->avanzar(7200);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 59.275);
        $tiempo->avanzar(5500);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 26.775);
        //Recargo porque tenemos poco saldo
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
        //Prueba pagar trasbordo un dia normal antes de los 60 minutos
        $tiempo->avanzar(60800);
        $this->assertEquals(date('d-m', $tiempo->time()), "02-01");
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 194.275);
        $tiempo->avanzar(3550);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 183.55);

        //Prueba pagar trasbordo un dia normal despues de los 60 minutos
        $tiempo->avanzar(7200);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 151.05);
        $tiempo->avanzar(5300);
        $this->assertEquals(date('N', $tiempo->time()), 5);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 118.55);

        //Prueba pagar trasbordo un sabado a la mañana despues de los 60 minutos
        $tiempo->avanzar(64800);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 86.05);
        $tiempo->avanzar(4100);
        $this->assertEquals(date('N', $tiempo->time()), 6);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 53.55);

        //Prueba pagar trasbordo un sabado a la mañana despues de los 60 minutos
        $tiempo->avanzar(28800);
        $this->assertTrue($tarjeta->recargar(100));
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 121.05);
        $tiempo->avanzar(5200);
        $this->assertEquals(date('N', $tiempo->time()), 6);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 110.325);

        //Prueba pagar trasbordo un domingo despues de los 60 minutos
        $tiempo->avanzar(57600);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 77.825);
        $tiempo->avanzar(5200);
        $this->assertEquals(date('N', $tiempo->time()), 7);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 67.1);
    }

    /*
    Pruebo pagar un trasbordo en distintos colectivos con tiempo normal
     */
    public function testUnTrasbordo()
    {
        $tiempo = new Tiempo();
        $tiempo->agregarFeriado("01-01-18");
        $this->AssertFalse($tiempo->esFeriado());
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);
        $colectivo3 = new Colectivo(155, "RosarioBus", 33);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 167.5);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 156.775);
        $colectivo3->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 124.275);
    }

    /*
    Pruebo pagar un trasbordo en distintos colectivos con tiempo normal
     */
    public function testTrasbordo2()
    {
        $tiempo = new Tiempo();
        $tiempo->agregarFeriado("01-01-18");
        $this->AssertFalse($tiempo->esFeriado());
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);
        $colectivo1->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 167.5);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), 156.775);

    }
}
