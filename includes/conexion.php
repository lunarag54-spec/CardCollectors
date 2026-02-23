<?php
// Configuración de la base de datos
$host = "localhost";
$user = "root";
$pass = ""; // En XAMPP por defecto está vacío
$db   = "TiendaColeccionismo1";

// Crear la conexión usando la extensión mysqli
$conn = new mysqli($host, $user, $pass, $db);

// Verificar si hay errores de conexión
if ($conn->connect_error) {
    // Si falla, detiene todo y muestra el error
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a utf8 para evitar problemas con tildes o ñ
$conn->set_charset("utf8");

// Nota: No cerramos la etiqueta por seguridad en archivos que solo son lógica PHP.