<?php

namespace TrabajoTarjeta;

class Variables
{
   cons cargasPosibles = [
      [10, 10],
      [20, 20],
      [30, 30],
      [50, 50],
      [100, 100],
      [1119.90, 1300],
      [2114.11, 2600]
      ];

   const precioCompleto = 32.50;
   const precioCompleto_Transbordo = 0;

   const precioMedioBoletoEstudiantil = (precioCompleto::cargasPosibles)/2;
   const precioMedioBoletoEstudiantil_Transbordo = 0;

   const precioMedioBoletoUniversitario = (precioCompleto::cargasPosibles)/2;
   const precioMedioBoletoUniversitario_Transbordo = 0;
   const viajesUniversitariosPorDia = 2;

   const precioLibre = 0;
}
