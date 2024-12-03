<?php
$host = 'db';
$username = 'root';
$password = 'test';
$dbname = 'tareas';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}
?>
