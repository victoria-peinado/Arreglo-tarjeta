<?php
namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class CompletoTest extends TestCase
{

    /**
     * Comprueba que la tarjeta con franquicia completa pueda pagar boletos infinitos
     */
    public function testRestarBoletos()
    {
        $tiempo = new Tiempo;
        $recargable = new Recargable();
        $pagable = new Pagable();
        $completo = new Completo(0, $tiempo,$recargable);
        for (($i = 0); $i < 160; ++$i) {
            $this->assertEquals($pagable->PrestarSaldo("153",$completo), true);
           // $this->assertEquals($completo->restarSaldo("153"), true);
        }
        $this->assertEquals($pagable->PrestarSaldo("153",$completo), true);
    }
}
