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

   cons precioCompleto = 32.50;
   cons precioCompleto_Transbordo = 0;

   cons precioMedioBoletoEstudiantil = (precioCompleto::cargasPosibles)/2;
   cons precioMedioBoletoEstudiantil_Transbordo = 0;

   cons precioMedioBoletoUniversitario = (precioCompleto::cargasPosibles)/2;
   cons precioMedioBoletoUniversitario_Transbordo = 0;
   cons viajesUniversitariosPorDia = 2;

   cons precioLibre = 0;
}
