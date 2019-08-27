<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface
{
	use pagarBoleto;
	
    protected $UltimoValorPagado = null;
    protected $UltimaHora = 0;
    protected $UltimoColectivo;
	protected $id;
	protected $tiempo;
	protected $saldo = 0;
	protected $plus = 0;
	protected $pagoplus = 0;
	
    public function __construct($id, TiempoInterface $tiempo)
    {
        $this->id = $id; //Guarda el ID
        $this->tiempo = $tiempo; //Guarda la variable tiempo la cual le es inyectada
    }

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
    public function recargar($monto)   //INTERFACE
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
        $this->pagarPlus(); //NO DEBE PAGARLOS AL MOMENTO DE RECARGAR, CAMBIAR
        // Devuelve true si el monto ingresado es válido
        return true;
    }
	
	
    public function restarSaldo($linea){ //INTERFACE
		new pagarBoleto;
	}
    /**
     * Setea a 0 el "pago plus". Esta funcion se ejecutara cuando se emite el boleto.
     *
     * @return int
     *   La cantidad de plus que pago en la ultiima recarga.
     */
    public function obtenerPagoPlus()
    {
        $pagoplusaux = $this->pagoplus; // Se almacena en un auxiliar
        $this->pagoplus = 0; // se Reinicia
        return $pagoplusaux; // Se devuelve el auxiliar
    }


    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtenerSaldo()  //INTERFACE
    {
        return $this->saldo;
    }


    /**
     * Devuelve el ID de la tarjeta.
     *
     * @return int
     */
    public function obtenerId()
    {
        return $this->id; //Devuelve el id de la tarjeta
    }

    /**
     * Devuelve el ultimo valor pagado.
     *
     * @return float
     */
    public function valorPagado()
    {
        return $this->UltimoValorPagado; // Devuelve el ultimo valor que se pago
    }

    /**
     * Devuelve la ultima hora en la que se uso la tarjeta.
     *
     * @return int
     */
    public function ultimaHoraUsada()
    {
        return $this->UltimaHora; // Devuelve la ultima hora a la que se pago
    }

    /**
     * Devuelve si se utilizo un viaje plus.
     *
     * @return int
     */
    public function usoPlus()
    {
        return $this->plus; // Devuelve si se utilizo un viaje plus
    }
    /**
     * Setea a 0 el "pago plus". Esta funcion se ejecutara cuando se emite el boleto.
     *
     * @return int
     *   La cantidad de plus que pago en la ultiima recarga.
     */
    public function obtenerPagoPlus()
    {
        $pagoplusaux = $this->pagoplus; // Se almacena en un auxiliar
        $this->pagoplus = 0; // se Reinicia
        return $pagoplusaux; // Se devuelve el auxiliar
    }
}
