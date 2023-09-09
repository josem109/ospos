<?php
$hostname = "localhost";     // Cambia esto a la dirección de tu servidor MySQL local
$username = "root";    // Cambia esto a tu nombre de usuario de MySQL
$password = ""; // Cambia esto a tu contraseña de MySQL
$database = "ospos"; // Cambia esto al nombre de tu base de datos

// Intentar la conexión a la base de datos
$conexion = new mysqli($hostname, $username, $password, $database);

// Verificar si ocurrió un error en la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa a la base de datos: $database\n";

    // Consulta SQL para contar los registros en la tabla "mapping"
    $consulta = "SELECT COUNT(*) AS total_registros FROM mapping";
    $resultado = $conexion->query($consulta);

    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        $totalRegistros = $fila["total_registros"];
        echo "Número de registros en la tabla 'mapping': $totalRegistros\n";
    } else {
        echo "Error al contar los registros: " . $conexion->error;
    }

    // Cierra la conexión
    $conexion->close();
}

// Define las variables de conexión
$host = "45.154.57.16";
$username = "integracion";
$password = "!hh1Y1j24";
$database = "integracion";

// Intenta conectarse a la base de datos
try {
  $connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Si la conexión se realiza correctamente, muestra un mensaje de éxito
  echo "¡La conexión se realizó correctamente en Clicare!";
} catch (PDOException $e) {
  // Si la conexión falla, muestra un mensaje de error
  echo "¡Error de conexión a Clicare: $e->getMessage()!";
}

// Cierra la conexión
$connection = null;

?>
