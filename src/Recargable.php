<?php
namespace TrabajoTarjeta;

class Recargable 
{
	
	//
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
	
	public function Rrecargar($monto)
	{
		$valoresDeRecarga=VariablesConstantes::cargasPosibles;
		for ($ender=sizeof($valoresDeRecarga);$c<(ender-1);$c++) { //Diferentes montos a recargar
			if($monto==$valoresDeRecarga[c][0]){
				$monto= $valoresDeRecarga[c][1];
				return $monto;
			}
		}
		$monto = 0;
		return $monto;

	}
}
