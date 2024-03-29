<?php
namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class MedioUniTest extends TestCase
{

    /**
     * Comprueba que la tarjeta con media franquicia Universitaria puede restar boletos, solo 2 medios
     */
    public function testRestarBoletos()
    {
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $medio = new MedioUniversitario(0, $tiempo,$recargable);
		$saldoEsperado =0;

        $this->assertTrue($medio->recargar(100));
		$saldoEsperado =$saldoEsperado+100;
        $this->assertEquals($medio->obtenerSaldo(), 100);

        $this->assertEquals($medio->restarSaldo("153"), true);	//Paga 1 medio
		$saldoEsperado =$saldoEsperado-(32.50/2);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $this->assertEquals($medio->restarSaldo("153"), false);	//No puede pagar boleto porque no pasaron 5 minutos
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), false);

        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);	//Gasta el 2do medio
		$saldoEsperado =$saldoEsperado-(32.50/2);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $this->assertEquals($medio->restarSaldo("153"), false);	//No puede pagar boleto porque no pasaron 5 minutos

        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);	//Paga boleto comun
		$saldoEsperado =$saldoEsperado-(32.50);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);
		/*	INSERVIBLE
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->obtenerSaldo(), 55.60);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->obtenerSaldo(), 40.80);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->obtenerSaldo(), 26.00);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->obtenerSaldo(), 11.20);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $this->assertEquals($medio->obtenerSaldo(), 11.20);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->obtenerSaldo(), 11.20);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), false);
		*/

    }

    /**
     * Comprueba que la tarjeta con media franquicia Universitaria puede marcar una vez cada 5 minutos
     */
    public function testTiempoInvalido()
    {
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $medio = new MedioUniversitario(0, $tiempo,$recargable);

        $this->assertTrue($medio->recargar(1119.90));	//Carga 1300.00

        $this->assertEquals($medio->restarSaldo("153"), true);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $tiempo->avanzar(265);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(584);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->restarSaldo("153"), false);
    }

    /**
     * Comprueba que la tarjeta con media franquicia Universitaria tiene 2 medios, y cuando pasa el dia se reinician
     */
    public function testPasoDia()
    {
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $medio = new MedioUniversitario(0, $tiempo,$recargable);
		$saldoEsperado =0;

        $this->assertTrue($medio->recargar(100));
        $this->assertTrue($medio->recargar(100));
        $this->assertTrue($medio->recargar(100));
		$saldoEsperado=300;

        $tiempo->avanzar(27000);
        $this->assertEquals($medio->restarSaldo("153"), true);//Paga 1er medio
		$saldoEsperado =$saldoEsperado-(32.50/2);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(18000);
        $this->assertEquals($medio->restarSaldo("153"), true);//Paga 2do medio
		$saldoEsperado =$saldoEsperado-(32.50/2);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(20000);
        $this->assertEquals($medio->restarSaldo("153"), true);//Paga completo
		$saldoEsperado =$saldoEsperado-(32.50);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(21500);
        $this->assertEquals($medio->restarSaldo("153"), true);//Paga 1er medio
		$saldoEsperado =$saldoEsperado-(32.50/2);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(1500);
        $this->assertEquals($medio->restarSaldo("153"), true);//Paga 2do medio
		$saldoEsperado =$saldoEsperado-(32.50/2);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(10000);
        $this->assertEquals($medio->restarSaldo("153"), true);//Paga completo
		$saldoEsperado =$saldoEsperado-(32.50);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);
    }

    /*
    Pruebo pagar un trasbordo un dia feriado con 90 minutos de espera y el texto del boleto
     */
    public function testTrasbordoUni()
    {
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $tarjeta = new MedioUniversitario(0, $tiempo,$recargable);
        $tiempo->avanzar(42300);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
		$saldoEsperado =200;
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);
        $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50/2);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);	//Paga completo

        $tiempo->avanzar(4200);
        $boleto2 = $colectivo2->pagarCon($tarjeta);						//Paga medio transbordo
		$saldoEsperado=$saldoEsperado-((32.50/2)*0);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(38100);
        $colectivo1->pagarCon($tarjeta);								//Paga completo
		$saldoEsperado=$saldoEsperado-(32.50);
        $this->assertEquals(date('d-m', $tiempo->time()), "01-01");
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
		
        $tiempo->avanzar(3500);
        $boleto2 = $colectivo2->pagarCon($tarjeta);						//Paga medio transbordo
		$saldoEsperado=$saldoEsperado-((32.50/2)*0);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
    }
}
