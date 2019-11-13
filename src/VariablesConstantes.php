<?php

namespace TrabajoTarjeta;

class VariablesConstantes
{
   $cargasPosibles = [
		[10, 10],
		[20, 20],
		[30, 30],
		[50, 50],
		[100, 100],
		[1119.90, 1300],
		[2114.11, 2600]
	];

   $precioCompleto = 32.50;
   $precioCompleto_Transbordo = 0;

   $precioMedioBoletoEstudiantil = ($precioCompleto)/2;
   $precioMedioBoletoEstudiantil_Transbordo = 0;

   $precioMedioBoletoUniversitario = ($precioCompleto)/2;
   $precioMedioBoletoUniversitario_Transbordo = 0;
   $viajesUniversitariosPorDia = 2;

   $precioLibre = 0;
}
