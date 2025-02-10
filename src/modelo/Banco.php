<?php

require_once "../src/modelo/Cliente.php";
require_once "../src/modelo/Cuenta.php";
require_once "../src/modelo/TarjetaCredito.php";
require_once "../src/modelo/CuentaCorriente.php";
require_once "../src/modelo/CuentaAhorros.php";
require_once "../src/modelo/TipoCuenta.php";
require_once "../src/excepciones/ClienteNoEncontradoException.php";
require_once "../src/excepciones/CuentaNoEncontradaException.php";

/**
 * Clase Banco
 */
class Banco {

    /**
     * Comisión de mantenimiento de la cuenta corriente en euros
     * @var float
     */
    private float $comisionCC = 0;

    /**
     * Mínimo saldo para no pagar comisión
     * @var float
     */
    private float $minSaldoComisionCC = 0;

    /**
     * Interés de la cuenta de ahorros en porcentaje
     * @var float
     */
    private float $interesCA = 0;

    /**
     * Interés de la cuenta de ahorros en porcentaje
     * @var float
     */
    private float $bonificacionCA = 0;

    /**
     * Nombre del banco
     * @var string
     */
    private string $nombre;

    /**
     * Colección de clientes del banco
     * @var array
     */
    private array $clientes;

    /**
     * Colección de cuentas bancarias abiertas
     * @var array
     */
    private array $cuentas;

    /**
     * Constructor de la clase Banco
     * 
     * @param string $nombre Nombre del banco
     */
    public function __construct(...$args) {
        $this->setNombre($args[0] ?? "Desconocido");
        $this->setComisionCC($args[1][0] ?? 0);
        $this->setMinSaldoComisionCC($args[1][1] ?? 0);
        $this->setInteresCA($args[2][0] ?? 0);
        $this->setBonificacionCA($args[2][1] ?? 0);
        $this->setClientes();
        $this->setCuentas();
    }

    /**
     * Obtiene el nombre del banco
     * 
     * @return string
     */
    public function getNombre(): string {
        return $this->nombre;
    }

    /**
     * Obtiene los clientes del banco como array de aliases de los clientes
     * 
     * @return array
     */
    private function getClientes(): array {
        return ($this->clientes);
    }

    /**
     * Obtiene las cuentas del banco como array de aliases de las cuentas
     * 
     * @return array
     */
    private function getCuentas(): array {
        return ($this->cuentas);
    }

    /**
     * Establece el nombre del banco
     * 
     * @param string $nombre Nombre del banco
     * @return $this
     */
    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    /**
     * Establece la colección de clientes del banco
     *  
     * @param array $clientes Colección de clientes del banco
     * @return $this
     */
    public function setClientes(array $clientes = []) {
        $this->clientes = $clientes;
    }

    /**
     * Establece la colección de cuentas del banco
     *  
     * @param array $cuentas Colección de cuentas del banco
     * @return $this
     */
    public function setCuentas(array $cuentas = []) {
        $this->cuentas = $cuentas;
    }

    // Gestión de la colección de clientes del banco

    /**
     * Elimina un cliente de la lista de clientes del banco
     * @param string $dni
     */
    private function eliminaCliente(string $dni): void {
        unset($this->clientes[$dni]);
    }

    /**
     * Añade un clientes a la lista de clientes del banco
     * @param Cliente $cliente
     */
    private function agregaCliente(Cliente $cliente): void {
        $this->clientes[$cliente->getDni()] = $cliente;
    }

    /**
     * Predicado para saber si un cliente existe o no
     * 
     * @param string $dni
     * @return ?Cliente
     */
    private function existeCliente(string $dni): ?Cliente {
        return ($this->clientes[$dni] ?? null);
    }

    /**
     * Obtiene un cliente del banco (como alias del cliente en la colección)
     * 
     * @param string DNI del cliente
     * @return Cliente
     * @throws ClienteNoEncontradoException
     */
    private function getCliente(string $dni): Cliente {
        $cliente = $this->existeCliente($dni);
        if ($cliente) {
            return $cliente;
        } else {
            throw new ClienteNoEncontradoException($dni);
        }
    }

    // Gestión de la colección de cuentas del banco  

    /**
     * Elimina una cuenta de la lista de cuentas del banco
     * @param string $idCuenta
     */
    private function eliminaCuenta(string $idCuenta): void {
        unset($this->cuentas[$idCuenta]);
    }

    /**
     * Añade una cuenta a la lista de cuentas del banco
     * @param Cuenta $cuenta
     */
    private function agregaCuenta(Cuenta $cuenta): void {
        $this->cuentas[$cuenta->getId()] = $cuenta;
    }

    /**
     * Predicado para saber si una cuenta existe o no
     * 
     * @param string $idCuenta
     * @return ?Cuenta
     */
    private function existeCuenta(string $idCuenta): ?Cuenta {
        return ($this->cuentas[$idCuenta] ?? null);
    }

    /**
     * Obtiene una cuenta del banco (como alias de la cuenta en la colección)
     * 
     * @param string Id de la cuenta
     * @return Cuenta
     * @throws CuentaNoEncontradaException
     */
    public function getCuenta(string $idCuenta): Cuenta {
        $cuenta = $this->existeCuenta($idCuenta);
        if ($cuenta) {
            return $cuenta;
        } else {
            throw new CuentaNoEncontradaException($idCuenta);
        }
    }

    /**
     * Obtiene la comisión del banco
     * 
     * @return float
     */
    public function getComisionCC(): float {
        return $this->comisionCC;
    }

    /**
     * Obtiene el mínimo saldo sin comisión
     * 
     * @return float
     */
    public function getMinSaldoComisionCC(): float {
        return $this->minSaldoComisionCC;
    }

    /**
     * Obtiene el interés del banco
     * 
     * @return float
     */
    public function getInteresCA(): float {
        return $this->interesCA;
    }

    /**
     * Obtiene la bonificacion de cuenta de ahorroa
     * 
     * @return float
     */
    public function getBonificacionCA(): float {
        return $this->bonificacionCA;
    }

    /**
     * Establece la comision de cuenta corriente del banco
     * 
     * @param float $comisionCC Comisión del banco
     */
    public function setComisionCC(float $comisionCC): void {
        $this->comisionCC = $comisionCC;
    }

    /**
     * Establece el mínimo saldo para no pagar comisión
     * 
     * @param float $minSaldoComisionCC mínimo saldo sin comisión
     */
    public function setMinSaldoComisionCC(float $minSaldoComisionCC): void {
        $this->minSaldoComisionCC = $minSaldoComisionCC;
    }

    /**
     * Establece el interés de la cuenta de ahorros del banco
     * 
     * @param float $interesCA Interés del banco
     * @return $this
     */
    public function setInteresCA(float $interesCA): void {
        $this->interesCA = $interesCA;
    }

    /**
     * Establece la bonificacion de la cuenta de ahorros del banco
     * 
     * @param float $bonificacionCA Interés del banco
     */
    public function setBonificacionCA(float $bonificacionCA): void {
        $this->bonificacionCA = $bonificacionCA;
    }

    /**
     * Realiza un alta de cliente del banco
     * 
     * @param string $dni
     * @param string $nombre
     * @param string $apellido1
     * @param string $apellido2
     * @param string telefono
     * @param DateTime $fechaNacimiento
     * @return bool
     */
    public function altaCliente(string $dni, string $nombre, string $apellido1, string $apellido2, string $telefono, string $fechaNacimiento): void {
        $cliente = new Cliente($dni, $nombre, $apellido1, $apellido2, $telefono, $fechaNacimiento);
        $this->agregaCliente($cliente);
    }

    /**
     * Realiza una baja de cliente del banco
     * 
     * @param string $dni
     */
    public function bajaCliente(string $dni): void {
        $cliente = $this->getCliente($dni);
        $cuentas = $cliente->getIdCuentas();
        $cliente->setIdCuentas([]);
        foreach ($cuentas as $idCuenta) {
            $this->eliminaCuenta($idCuenta);
        }
        $this->eliminaCliente($dni);
    }

    /**
     * Obtiene un cliente del banco (como copia del cliente en la colección)
     * 
     * @param string DNI del cliente
     * @return Cliente
     * @throws ClienteNoEncontradoException
     */
    public function obtenerCliente(string $dni): Cliente {
        return clone($this->getCliente($dni));
    }

    /**
     * Obtiene los clientes del banco como array de copias de los clientes
     * 
     * @return array
     */
    public function obtenerClientes(): array {
        return array_map(fn($cliente) => clone($cliente), $this->getClientes());
    }

    /**
     * Crea una cuenta corriente de un cliente del banco
     * 
     * @param string $dni
     * @param float $saldo
     */
    public function altaCuentaCorrienteCliente(string $dni): string {
        $cliente = $this->getCliente($dni);
        $cuenta = new CuentaCorriente($dni);
        $this->agregaCuenta($cuenta);
        $cliente->altaCuenta($cuenta->getId());
        return $cuenta->getId();
    }

    /**
     * Crea una cuenta de ahorros de un cliente del banco
     * 
     * @param string $dni
     * @param bool libreta
     */
    public function altaCuentaAhorrosCliente(string $dni, bool $libreta = false): string {
        $cliente = $this->getCliente($dni);
        $cuenta = new CuentaAhorros($dni, $this->getBonificacionCA(), $libreta);
        $this->agregaCuenta($cuenta);
        $cliente->altaCuenta($cuenta->getId());
        return $cuenta->getId();
    }

    /**
     * Elimina una cuenta de un cliente del banco
     * 
     * @param string $dni
     * @param string $idCuenta
     */
    public function bajaCuentaCliente(string $dni, string $idCuenta): void {
        $cliente = $this->getCliente($dni);
        if ($cliente->existeIdCuenta($idCuenta)) {
            $this->eliminaCuenta($idCuenta);
            $cliente->bajaCuenta($idCuenta);
        }
    }

    /**
     * Obtener cuenta bancaria (como copia del objeto cuenta del banco)
     * 
     * @param string $idCuenta
     * @return type
     */
    public function obtenerCuenta(string $idCuenta): Cuenta {
        return clone($this->getCuenta($idCuenta));
    }

    /**
     * Obtiene las cuentas del banco (como array de copias de la colección)
     * 
     * @return array
     */
    public function obtenerCuentas(): array {
        return array_map(fn($cuenta) => $this->obtenerCuenta($cuenta), $this->getCuentas);
    }

    /**
     * Operación de ingreso en una cuenta de un cliente
     * 
     * @param string $dni
     * @param string $idCuenta
     * @param float $cantidad
     * @param string $descripcion
     */
    public function ingresoCuentaCliente(string $dni, string $idCuenta, float $cantidad, string $descripcion): void {
        $cliente = $this->getCliente($dni);
        if ($cliente->existeIdCuenta($idCuenta)) {
            $cuenta = $this->getCuenta($idCuenta);
            $cuenta->ingreso($cantidad, $descripcion);
        }
    }

    /**
     * Realiza un debito a una cuenta del banco
     * 
     * @param string $dni
     * @param string $idCuenta
     * @param float $cantidad
     * @param string $descripcion
     */
    public function debitoCuentaCliente(string $dni, string $idCuenta, float $cantidad, string $descripcion): void {
        $cliente = $this->getCliente($dni);
        if ($cliente->existeIdCuenta($idCuenta)) {
            $cuenta = $this->getCuenta($idCuenta);
            $cuenta->debito($cantidad, $descripcion);
        }
    }

    /**
     * Operación para realizar una transferencia de una cuenta de un cliente a otra
     * 
     * @param string $dniClienteOrigen
     * @param string $dniClienteDestino
     * @param string $idCuentaOrigen
     * @param string $idCuentaDestino
     * @param float $cantidad
     */
    public function realizaTransferencia(string $dniClienteOrigen, string $dniClienteDestino, string $idCuentaOrigen, string $idCuentaDestino, float $cantidad): void {
        $clienteOrigen = $this->getCliente($dniClienteOrigen);
        $clienteDestino = $this->getCliente($dniClienteDestino);
        if ($clienteOrigen->existeIdCuenta($idCuentaOrigen) && $clienteDestino->existeIdCuenta($idCuentaDestino)) {
            $this->debitoCuentaCliente($dniClienteOrigen, $idCuentaOrigen, $cantidad, "Transferencia de $cantidad € desde su cuenta $idCuentaOrigen a la cuenta $idCuentaDestino");
            $this->ingresoCuentaCliente($dniClienteDestino, $idCuentaDestino, $cantidad, "Transferencia de $cantidad € a su cuenta $idCuentaDestino desde la cuenta $idCuentaOrigen");
        }
    }

    /**
     * Aplica cargos de comisión a la cuenta corriente
     */
    public function aplicaComisionCC(): void {
        $cuentasCorrientes = array_filter($this->getCuentas(), fn($cuenta) => $cuenta instanceof CuentaCorriente);

// Captura las propiedades necesarias con 'use'
        $comisionCC = $this->getComisionCC();
        $minSaldoComisionCC = $this->getminSaldoComisionCC();

        array_walk($cuentasCorrientes, function ($cuentaCC) use ($comisionCC, $minSaldoComisionCC) {
            $cuentaCC->aplicaComision($comisionCC, $minSaldoComisionCC);
        });
    }

    /**
     * Aplica intereses a la cuenta de ahorros
     */
    public function aplicaInteresCA(): void {
        $cuentasAhorros = array_filter($this->getCuentas(), fn($cuenta) => $cuenta instanceof CuentaAhorros);
// Captura las propiedades necesarias con 'use'
        $interesCA = $this->getInteresCA();
        array_walk($cuentasAhorros, function ($cuentaCA) use ($interesCA) {
            $cuentaCA->aplicaInteres($interesCA);
        });
    }

    /**
     * Crea una tarjeta de un cliente del banco
     * 
     * @param string $dni
     * @param float $saldo
     */
    public function altaTarjetaCreditoCliente(string $dni): TarjetaCredito {
        $cliente = $this->getCliente($dni);
        $tarjeta = new TarjetaCredito(10000);
        return $tarjeta;
    }

    /**
     * Realiza un ingreso a un producto bancario cualquiera
     * 
     * @param string $dni
     * @param IProductoBancario $productoBancario
     * @param float $cantidad
     * @param string $descripcion
     */
    public function ingresoProductoBancarioCliente(IProductoBancario $productoBancario, float $cantidad, string $descripcion): void {
        $productoBancario->ingreso($cantidad, $descripcion);
    }

    /**
     * Realiza un debito a un producto bancario cualquiera
     * @param string $dni
     * @param IProductoBancario $productoBancario
     * @param float $cantidad
     * @param string $descripcion
     */
    public function debitoProductoBancarioCliente(IProductoBancario $productoBancario, float $cantidad, string $descripcion): void {
        $productoBancario->debito($cantidad, $descripcion);
    }
}
