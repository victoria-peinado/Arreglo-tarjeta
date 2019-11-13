<?php

namespace TrabajoTarjeta;

class VariablesConstantes
{
   public $cargasPosibles = [
		[10, 10],
		[20, 20],
		[30, 30],
		[50, 50],
		[100, 100],
		[1119.90, 1300],
		[2114.11, 2600]
	];

	public $precioCompleto = 32.50;
	public $precioCompleto_Transbordo = 0;
	
	public $precioMedioBoletoEstudiantil = ($precioCompleto)/2;
	public $precioMedioBoletoEstudiantil_Transbordo = 0;
	
	public $precioMedioBoletoUniversitario = ($precioCompleto)/2;
	public $precioMedioBoletoUniversitario_Transbordo = 0;
	public $viajesUniversitariosPorDia = 2;

	public  $precioLibre = 0;
}
