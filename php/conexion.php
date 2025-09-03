<?php
/*$host = "localhost";
$usuario = "root";
$contrasena = ""; // Si usas XAMPP y no tienes clave
$base_datos = "tulipan";

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}*/
// Ajusta estas credenciales a tu entorno
const DB_HOST = 'localhost';
const DB_NAME = 'tulipan';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO {
    static $pdo;
    if ($pdo) return $pdo;
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();



?>
