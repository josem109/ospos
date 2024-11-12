<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['functionname']) && $_POST['functionname'] === 'getDolar') {
    
    $arrContextOptions = [
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
        ],
    ]; 

    // Función para verificar si hay conexión a Internet
    function checkInternetConnection($host = 'www.google.com', $port = 80, $timeout = 10) {
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (is_resource($connection)) {
            fclose($connection);
            return true;  // Hay conexión
        }
        return false;  // No hay conexión
    }

    // Función para hacer una solicitud y retornar la respuesta JSON
    function fetchDataFromApi($url, $contextOptions) {
        $response = file_get_contents($url, false, stream_context_create($contextOptions));
        return $response ? json_decode($response, true) : null;
    }

    // Conexión a la base de datos
    try {
        $db = new PDO('mysql:host=localhost;dbname=ospos', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        date_default_timezone_set('America/Caracas');
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
        return;
    }

    // Primera solicitud a la API para obtener el registro 'usd' en `monitors`
    $apiUrl1 = "https://pydolarve.org/api/v1/dollar?page=bcv";
    $data1 = fetchDataFromApi($apiUrl1, $arrContextOptions);

    if ($data1 && isset($data1['monitors']['usd'])) {
        $usdData = $data1['monitors']['usd'];
        
        // La fecha en la API es la de mañana, por lo tanto calculamos la fecha de hoy y la de mañana
        $fechaManana = DateTime::createFromFormat('d/m/Y, h:i A', $usdData['last_update'])->format('Y-m-d');
        $fechaHoy = date('Y-m-d'); // Fecha actual
        $value = $usdData['price'];
        $currency_symbol = 'USD';

        try {
            // Paso 1: Comprobar si existe un registro para el `currency_symbol` y `fechaManana`
            $queryCheck = $db->prepare("SELECT id FROM `ospos_currencytable` WHERE `currency_symbol` = :currency_symbol AND `currency_date` = :currency_date");
            $queryCheck->bindParam(':currency_symbol', $currency_symbol);
            $queryCheck->bindParam(':currency_date', $fechaManana);
            $queryCheck->execute();
            $row = $queryCheck->fetch(PDO::FETCH_ASSOC);
        
            if ($row) {
                // Si existe el registro, actualizar el valor de `currency_rate` para mañana
                $queryUpdate = $db->prepare("UPDATE `ospos_currencytable` SET `currency_rate` = :currency_rate WHERE `id` = :id");
                $queryUpdate->bindParam(':currency_rate', $value);
                $queryUpdate->bindParam(':id', $row['id']);
                $queryUpdate->execute();
            } else {
                // Si no existe el registro para mañana, insertar un nuevo valor
                $queryInsert = $db->prepare("INSERT INTO `ospos_currencytable` (currency_rate, currency_symbol, currency_date) VALUES (:currency_rate, :currency_symbol, :currency_date)");
                $queryInsert->bindParam(':currency_rate', $value);
                $queryInsert->bindParam(':currency_symbol', $currency_symbol);
                $queryInsert->bindParam(':currency_date', $fechaManana);
                $queryInsert->execute();
            }
        
            // Paso 2: Buscar el valor de hoy en `ospos_currencytable`
            $queryToday = $db->prepare("SELECT currency_rate FROM `ospos_currencytable` WHERE `currency_symbol` = :currency_symbol AND `currency_date` = :currency_date");
            $queryToday->bindParam(':currency_symbol', $currency_symbol);
            $queryToday->bindParam(':currency_date', $fechaHoy);
            $queryToday->execute();
            $rowToday = $queryToday->fetch(PDO::FETCH_ASSOC);
        
            if ($rowToday) {
                // Si el valor de hoy existe, actualizar `ospos_app_config`
                $todayRate = $rowToday['currency_rate'];
                $updateConfig = $db->prepare("UPDATE `ospos_app_config` SET `value` = :dolarValue WHERE `key` = 'currency_rate'");
                $updateConfig->bindParam(':dolarValue', $todayRate);
                $updateConfig->execute();
            } else {
                echo json_encode(['error' => "No se encontró el valor de la tasa de cambio para hoy en 'ospos_currencytable'."]);
                return;
            }
        
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al insertar o actualizar la tasa de cambio: ' . $e->getMessage()]);
            return;
        }
    } else {
        echo json_encode(['error' => "No se encontró el registro 'usd' en la respuesta de la API."]);
        return;
    }

    // Segunda solicitud a otra API para obtener el valor de `price`
    $apiUrl2 = "https://pydolarve.org/api/v1/dollar?monitor=enparalelovzla";
    $data2 = fetchDataFromApi($apiUrl2, $arrContextOptions);

    if ($data2 && isset($data2['price'])) {
        try {
            // Actualizar en `ospos_app_config` el valor de `currency_rate_alternative`
            $query = $db->prepare("UPDATE `ospos_app_config` SET `value` = :dolarValue WHERE `key` = 'currency_rate_alternative'");
            $query->bindParam(':dolarValue', $data2['price']);
            $query->execute();
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error al actualizar el valor alternativo: ' . $e->getMessage()]);
            return;
        }
    } else {
        echo json_encode(['error' => "No se encontró el campo 'price' en la segunda respuesta."]);
        return;
    }

    echo json_encode(['success' => 'Valores de dólar actualizados correctamente en la base de datos.']);
} else {
    echo json_encode(['error' => 'Método no permitido o función no especificada.']);
}
?>
