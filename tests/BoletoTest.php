<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase
{

    /**
     * Comprueba que sucede cuando creamos un boleto nuevo.
     */
    public function testSaldoCero()
    {

        $tiempo = new Tiempo();
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $pagable = new Pagable();
        $colectivo = new Colectivo(133, "RosarioBus", 69,$pagable);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 0);
        $tarjeta->recargar(50);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 17.5);

    }

    /**
     * Comprueba retorno de datos Tarjeta Normal
     */
    public function testDatosBoletoTarjeta()
    {
        $colectivo = new Colectivo(133, "RosarioBus", 69);
        $tiempo = new TiempoFalso();
        $recargable = new Recargable();
        $tarjeta = new Tarjeta(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tiempo->avanzar(250);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 67.5);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 35);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 2.5);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 2.5);//un plus
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 2.5);//ult plus
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto, False);

        $tarjeta->recargar(100);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 5);
    }

    /**
     * Comprueba retorno de datos Medio
     */
    public function testDatosBoletoMedio()
    {
        $colectivo = new Colectivo(133, "RosarioBus", 69);
        $tiempo = new TiempoFalso();
        $recargable = new Recargable();
        $tarjeta = new Medio(0, $tiempo,$recargable);
        $tarjeta->recargar(100);
        $tiempo->avanzar(250);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 83.75);


        $tiempo->avanzar(300);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 67.5);

        $tiempo->avanzar(300);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 51.25);
        
        $boleto = $colectivo->pagarCon($tarjeta);// caso de que no pasaron 5 minutos
        $this->assertEquals($boleto, False);


    }
    /**
     * Comprueba retorno de datos Medio Universitario
     */
    public function testDatosBoletoMedioUni()
    {
        $colectivo = new Colectivo(133, "RosarioBus", 69);
        $tiempo = new TiempoFalso();
        $recargable = new Recargable();
        $tarjeta = new \TrabajoTarjeta\MedioUniversitario(0, $tiempo,$recargable);
        $tiempo->avanzar(250);
        $tarjeta->recargar(100);
        $tiempo->avanzar(250);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 83.75);


        $tiempo->avanzar(300);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 67.5);

        $tiempo->avanzar(300);
        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 35);//usa saldo normal porque ya uso sus dos medios
        
        $boleto = $colectivo->pagarCon($tarjeta);// caso de que no pasaron 5 minutos
        $this->assertEquals($boleto, False);

    }

    /**
     * Comprueba retorno de datos Completo
     */
    public function testDatosBoletoCompleto()
    {
        $colectivo = new Colectivo(133, "RosarioBus", 69);
        $tiempo = new TiempoFalso();
        $recargable = new Recargable();
        $tarjeta = new \TrabajoTarjeta\Completo(0, $tiempo,$recargable);

       
       

        $boleto = $colectivo->pagarCon($tarjeta);
        $this->assertEquals($boleto->obtenerSaldo(), 0);
    }

}  