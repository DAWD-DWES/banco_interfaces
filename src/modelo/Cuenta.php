<?php

require_once "../src/modelo/Operacion.php";
require_once "../src/modelo/TipoCuenta.php";
require_once "../src/modelo/IProductoBancario.php";
require_once "../src/excepciones/SaldoInsuficienteException.php";

/**
 * Clase Cuenta 
 */
abstract class Cuenta implements IProductoBancario {

    /**
     * Id de la cuenta
     * @var string
     */
    private string $id;

    /**
     * Saldo de la cuenta
     * @var float
     */
    private float $saldo;

    /**
     * Tipo de la cuenta
     * @var TipoCuenta
     */
    private TipoCuenta $tipo;

    /**
     * Timestamp de Fecha y hora de creación de la cuenta
     * @var DateTime
     */
    private DateTime $fechaCreacion;

    /**
     * Id del cliente dueño de la cuenta
     * @var string
     */
    private string $idCliente;

    /**
     * Operaciones realizadas en la cuenta
     * @param float $saldo
     */
    private array $operaciones;

    public function __construct(string $dniCliente, TipoCuenta $tipo) {
        $this->setId(uniqid());
        $this->setSaldo(0);
        $this->setOperaciones([]);
        $this->setFechaCreacion(new DateTime('now'));
        $this->tipo = $tipo;
        $this->setIdCliente($dniCliente);
    }

    public function __clone() {
        $this->setOperaciones(array_map(fn($operacion) => clone ($operacion), $this->getOperaciones()));
    }

    public function getId(): string {
        return $this->id;
    }

    public function getSaldo(): float {
        return $this->saldo;
    }

    public function getIdCliente(): string {
        return $this->idCliente;
    }

    function getFechaCreacion(): DateTime {
        return $this->fechaCreacion;
    }

    private function getOperaciones(): array {
        return $this->operaciones;
    }

    public function getTipo(): TipoCuenta {
        return $this->tipo;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setSaldo($saldo) {
        $this->saldo = $saldo;
    }

    public function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    function setFechaCreacion(DateTime $fechaCreacion): void {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setTipo(TipoCuenta $tipo): void {
        $this->tipo = $tipo;
    }

    private function setOperaciones(array $operaciones) {
        $this->operaciones = $operaciones;
    }

    public function obtenerOperaciones(): array {
        return array_map(fn($operacion) => clone ($operacion), $this->getOperaciones());
    }

    /**
     * Ingreso de una cantidad en una cuenta
     * @param type $cantidad Cantidad de dinero
     * @param type $descripcion Descripción del ingreso
     */
    public function ingreso(float $cantidad, string $descripcion): void {
        if ($cantidad > 0) {
            $operacion = new Operacion(TipoOperacion::INGRESO, $cantidad, $descripcion);
            $this->agregaOperacion($operacion);
            $this->setSaldo($this->getSaldo() + $cantidad);
        }
    }

    /**
     * Extracción de una cantidad
     * @param type $cantidad Cantidad de dinero a retirar
     * @param type $descripcion Descripcion del debito
     */
    abstract public function debito(float $cantidad, string $descripcion): void;

    public function __toString() {
        $saldoFormatted = number_format($this->getSaldo(), 2); // Formatear el saldo con dos decimales
        $operacionesStr = implode("</br>", array_map(fn($operacion) => "{$operacion->__toString()}", $this->getOperaciones())); // Convertir las operaciones en una cadena separada por saltos de línea

        return "Cuenta ID: {$this->getId()}</br>" .
                "Tipo Cuenta: " . ($this->getTipo())->value . "</br>" .
                "Cliente ID: {$this->getIdCliente()}</br>" .
                "Saldo: $saldoFormatted</br>" .
                "Fecha Creación: {$this->getFechaCreacion()->format('Y-m-d')}</br>" .
                "$operacionesStr";
    }

    /**
     * Agrega operación a la lista de operaciones de la cuenta
     * @param type $operacion Operación a añadir
     */
    protected function agregaOperacion(Operacion $operacion) {
        $this->operaciones[] = $operacion;
    }
}
