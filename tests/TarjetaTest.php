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
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);

		//Pruebo pagar un trasbordo un dia feriado con 90 minutos de espera y el texto del boleto
        $tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;

        $boleto = $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals(date('G', $tiempo->time()), '0');
        $this->assertEquals(date('d-m', $tiempo->time()), "01-01");
        $this->assertEquals($boleto->obtenerFecha(), "01/01/1970 00:00:00");
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
        $tiempo->avanzar(4200);	//"01/01/1970 01:10:00"

        $boleto2 = $colectivo2->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50*0);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        //Pruebo pagar un trasbordo en un mismo colectivo
        $tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(2300);			//"01/01/1970 00:38:00"
        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        //Pruebo pagar un trasbordo un dia feriado cuando ya pasaron los 120 minutos
        $tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tiempo->avanzar(28800);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;
		
        $this->assertEquals(date('d-m', $tiempo->time()), "01-01");

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
		
        $tiempo->avanzar(7300);	//"01/01/1970 02:01:00"
        $colectivo2->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        //Prueba pagar trasbordo un dia normal antes de los 60 minutos
        $tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;
		
        $tiempo->avanzar(86400);	//Avanzar 1 dia //"01/02/1970 00:00:00"
        $this->assertEquals(date('d-m', $tiempo->time()), "02-01");

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(3550);		//"01/02/1970 00:59:00"
        $colectivo2->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50*0.33);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        //Prueba pagar trasbordo un dia normal despues de los 60 minutos
        $tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;
		
        $tiempo->avanzar(86400);	//Avanzar 1 dia		//"01/02/1970 00:00:00"
        $tiempo->avanzar(28800);	//Avanzar 8 horas	//"01/02/1970 08:00:00"
        $this->assertEquals(date('d-m', $tiempo->time()), "02-01");

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(3650);		//Avanzar 59 minutos	//"01/02/1970 08:59:00"
        $colectivo2->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        //Prueba pagar trasbordo un sabado a la mañana despues de los 60 minutos
        $tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;
		
        $tiempo->avanzar(86400);	//Avanzar 1 dia	//"01/02/1970 00:00:00"
        $tiempo->avanzar(86400);	//Avanzar 1 dia	//"01/03/1970 00:00:00"

        $this->assertEquals(date('d-m', $tiempo->time()), "03-01");	//El 03/01/1970 cae un sabado
        $this->assertEquals(date('N', $tiempo->time()), 6);

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(7100);
        $colectivo2->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50*0);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        //Prueba pagar trasbordo un domingo despues de los 60 minutos
        $tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;
		
        $tiempo->avanzar(86400);	//Avanzar 1 dia	//"01/02/1970 00:00:00"
        $tiempo->avanzar(86400);	//Avanzar 1 dia	//"01/03/1970 00:00:00"
        $tiempo->avanzar(86400);	//Avanzar 1 dia	//"01/04/1970 00:00:00"
        $this->assertEquals(date('d-m', $tiempo->time()), "03-01");	//El 04/01/1970 cae un domingo
        $this->assertEquals(date('N', $tiempo->time()), 7);

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(7100);		//Avanzar 1 hora y 59 minutos	//"01/02/1970 01:59:00"
		$saldoEsperado=$saldoEsperado-(32.50*0);
        $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
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
		$saldoEsperado =200;
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);
        $colectivo3 = new Colectivo(155, "RosarioBus", 33);

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-32.50;
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $colectivo2->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50*0);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $colectivo3->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
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
		$saldoEsperado =200;
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);

        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50*0);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $colectivo2->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50*0);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

    }
}
