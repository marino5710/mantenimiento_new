<?php
try {
    // Cargar las variables del archivo .env
    $host = $_ENV['DB_HOST'];
  $port = $_ENV['DB_PORT'];
  $dbname = $_ENV['DB_NAME'];
  $user = $_ENV['DB_USER'];
  $pass = $_ENV['DB_PASSWORD'];

    // Crear conexión PDO a MySQL
    $db = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Conexión exitosa a la base de datos MySQL";

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
