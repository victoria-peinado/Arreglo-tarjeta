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
        $pagable = new Pagable();
        $medio = new Medio(0, $tiempo,$recargable);
        $this->assertTrue($medio->recargar(20));
        $this->assertEquals($medio->obtenerSaldo(), 20);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);//valor pasaje 7.4
        $tiempo->avanzar(300);
        $this->assertEquals($medio->obtenerSaldo(), 12.6);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $this->assertEquals($medio->obtenerSaldo(), 5.2);
        $tiempo->avanzar(300);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);//debe un medio
        $tiempo->avanzar(300);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);//debe otro medio
        $tiempo->avanzar(300);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);//no puedo pagar el pasaje
        $this->assertTrue($medio->recargar(962.59)); //recarga 1184.17
        $this->assertEquals($medio->obtenerSaldo(), 1189.37);// no es 1159.77 porque no se resta el plus al recargar
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(300);
        for (($i = 0); $i < 155; ++$i) {
            $this->assertEquals($medio->restarSaldo("153"), true);
            $tiempo->avanzar(300);
        }
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
    }

    /**
     * prueba la limitacion de tiempo de 5 minutos
     */
    public function testTiempoInvalido()
    {
        $tiempo = new TiempoFalso;
        $recargable = new Recargable();
        $pagable = new Pagable();
        $medio = new Medio(0, $tiempo,$recargable);
        $this->assertTrue($medio->recargar(962.59));
        $this->assertEquals($medio->restarSaldo("153"), true);
        $tiempo->avanzar(300);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $tiempo->avanzar(50);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(50);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(50);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(50);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(50);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(50);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $tiempo->avanzar(265);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
        $tiempo->avanzar(584);
        $this->assertEquals($medio->restarSaldo("153"), true);
        $this->assertEquals($pagable->PrestarSaldo("153",$medio), true);
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

        /*
        Pruebo pagar un trasbordo un dia feriado con 90 minutos de espera y el texto del boleto
         */
        $boleto = $colectivo1->pagarCon($tarjeta);
        $this->assertEquals(date('N', $tiempo->time()), '4');
        $this->assertEquals(date('G', $tiempo->time()), '8');
        $this->assertEquals(date('d-m', $tiempo->time()), "01-01");
        $this->assertEquals($boleto->obtenerFecha(), "01/01/1970 08:00:00");
        $this->assertEquals($tarjeta->obtenerSaldo(), 192.6);
        $tiempo->avanzar(4200);
        $boleto2 = $colectivo2->pagarCon($tarjeta);
        $this->assertEquals($boleto2->obtenerDescripcion(), "Trasbordo Medio 2.442");
        $this->assertEquals($tarjeta->obtenerSaldo(), 190.158);
    }
}
