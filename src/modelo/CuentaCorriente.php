<?php

require_once "../src/modelo/Cuenta.php";
require_once "../src/modelo/TipoCuenta.php";

/**
 * Clase CuentaCorriente 
 */
class CuentaCorriente extends Cuenta {

    public function __construct(string $idCliente) {
        parent::__construct($idCliente, TipoCuenta::CORRIENTE);
    }
    
    /**
     * 
     * @param type $cantidad Cantidad de dinero a retirar
     * @param type $descripcion Descripcion del debito
     */
    public function debito(float $cantidad, string $descripcion): void {
            $operacion = new Operacion(TipoOperacion::DEBITO, $cantidad, $descripcion);
            $this->agregaOperacion($operacion);
            $this->setSaldo($this->getSaldo() - $cantidad);
    }

    public function aplicaComision($comision, $minSaldo): void {
        if ($this->getSaldo() < $minSaldo) {
            $this->debito($comision, "Cargo de comisión de mantenimiento");
        }
    }
}
