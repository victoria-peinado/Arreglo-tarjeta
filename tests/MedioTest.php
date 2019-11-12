<?php
namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class MedioTest extends TestCase
{

    /**
     * Comprueba que la tarjeta con media franquicia no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido()
    {
        $tiempo = new Tiempo;
        $recargable = new Recargable();
        $medio = new Medio(0, $tiempo,$recargable);
        $this->assertFalse($medio->recargar(15));
        $this->assertEquals($medio->obtenerSaldo(), 0);
    }

    /**
     * Comprueba que la tarjeta con media franquicia puede restar boletos, con la limitacion de tiempo
     */
    public function testRestarBoletos()
    {
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $medio = new Medio(0, $tiempo,$recargable);
		$saldoEsperado =0;
        $this->assertTrue($medio->recargar(100));//Prueba si carga
		$saldoEsperado=$saldoEsperado+100;
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $this->assertEquals($medio->restarSaldo("153"), true);//Medio Comun
		$saldoEsperado=$saldoEsperado-(32.50 /2);
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);//Comprueba que avanzar 5 minutos permite usar un medio
		$saldoEsperado=$saldoEsperado-((32.50 /2));
        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);
		
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $medio = new Medio(0, $tiempo,$recargable);
		$saldoEsperado =0;

        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);	//debe un medio
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);	//debe otro medio
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), false);	//no puedo pagar el pasaje

        $this->assertTrue($medio->recargar(1119.90));			//recarga 1300.00
		$saldoEsperado=$saldoEsperado+1300.00;

        $this->assertEquals($medio->obtenerSaldo(), $saldoEsperado);	// no es 1159.77 porque no se resta el plus al recargar
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->restarSaldo("153"), false);	//Comprueba que sin avanzar 5 minutos no se puede realizar otro pago de boleto
        $tiempo->avanzar(300);

        for (($i = 0); $i < 10; ++$i) {
            $this->assertEquals($medio->restarSaldo("153"), true);
            $tiempo->avanzar(300);
        }

        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->restarSaldo("153"), false);
    }

    /**
     * prueba la limitacion de tiempo de 5 minutos
     */
    public function testTiempoInvalido()
    {
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $medio = new Medio(0, $tiempo,$recargable);
		$saldoEsperado =0;

        $this->assertTrue($medio->recargar(1119.90));	//Carga 1300.00
        $this->assertEquals($medio->restarSaldo("153"), true);
		$saldoEsperado =$saldoEsperado+1300.00;

        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
		$saldoEsperado =$saldoEsperado-(32.50/2);

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
		$saldoEsperado=$saldoEsperado-(32.50/2);

        $tiempo->avanzar(265);
        $this->assertEquals($medio->restarSaldo("153"), false);
        $tiempo->avanzar(584);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($medio->restarSaldo("153"), false);
    }

    /*
    Se testea si se puede pagar un trasbordo un dia feriado con 90 minutos de espera y cual el texto del boleto
     */
    public function testTrasbordoMedio()
    {
        $tiempo = new TiempoFalso;//$tiempo = new TiempoFalso(0);
        $recargable = new Recargable();
        $tarjeta = new Medio(0, $tiempo,$recargable);
        $tiempo->avanzar(28800);
        $tarjeta->recargar(100);
        $tarjeta->recargar(100);
        $colectivo1 = new Colectivo(122, "Semtur", 37);
        $colectivo2 = new Colectivo(134, "RosarioBus", 52);
		$saldoEsperado =200;

        /*
        Pruebo pagar un trasbordo un dia feriado con 90 minutos de espera y el texto del boleto
         */
        $boleto = $colectivo1->pagarCon($tarjeta);
		$saldoEsperado=$saldoEsperado-(32.50/2);
        $this->assertEquals(date('N', $tiempo->time()), '4');
        $this->assertEquals(date('G', $tiempo->time()), '8');
        $this->assertEquals(date('d-m', $tiempo->time()), "01-01");
        $this->assertEquals($boleto->obtenerFecha(), "01/01/1970 08:00:00");
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);

        $tiempo->avanzar(4200);
		$saldoEsperado=$saldoEsperado-((32.50/2)*0.33);
		$stringEsperado="Trasbordo Medio " . $saldoEsperado;
        $boleto2 = $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($boleto2->obtenerDescripcion(), $stringEsperado);
        $this->assertEquals($tarjeta->obtenerSaldo(), $saldoEsperado);
    }
}
