<?php 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['functionname']) && $_POST['functionname'] === 'updateRates') {
    // Se asume que se envían 'bcv' y 'alt' con los valores de las tasas
    if(!isset($_POST['bcv']) || !isset($_POST['alt'])) {
        echo json_encode(['error' => 'No se recibieron los valores de bcv y/o alt.']);
        return;
    }

    $bcvValue = trim($_POST['bcv']);
    $altValue = trim($_POST['alt']);

    // Validar que sean valores numéricos
    if(!is_numeric($bcvValue) || !is_numeric($altValue)) {
        echo json_encode(['error' => 'Los valores deben ser numéricos.']);
        return;
    }

    // Asegurar que tenemos una fecha actual y la de mañana
    date_default_timezone_set('America/Caracas');
    $fechaHoy = date('Y-m-d'); // Fecha actual
    // Para simular la lógica original, asumimos que el valor enviado para bcvValue es el valor de mañana
    // Si el valor se desea guardar para hoy, se ajusta la lógica. Aquí lo haré igual que el original:
    // El original guardaba el valor "futuro" en fechaManana y actualizaba el de hoy. Pero ahora
    // no tenemos futuro ni pasado, así que se asume que bcvValue es el valor de mañana.
    // Si quieres que ambos valores se consideren como "hoy", simplemente usa $fechaHoy.
    // Por ahora mantendré una lógica similar, donde bcvValue se asocia a una fecha futura:
    
    $fechaManana = date('Y-m-d');
    $currency_symbol = 'USD';

    try {
        $db = new PDO('mysql:host=localhost;dbname=ospos', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Paso 1: Actualizar/Insertar valor de mañana (USD) en `ospos_currencytable`
        $queryCheck = $db->prepare("SELECT id FROM `ospos_currencytable` WHERE `currency_symbol` = :currency_symbol AND `currency_date` = :currency_date");
        $queryCheck->bindParam(':currency_symbol', $currency_symbol);
        $queryCheck->bindParam(':currency_date', $fechaManana);
        $queryCheck->execute();
        $row = $queryCheck->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Si existe el registro, actualizar el valor para mañana
            $queryUpdate = $db->prepare("UPDATE `ospos_currencytable` SET `currency_rate` = :currency_rate WHERE `id` = :id");
            $queryUpdate->bindParam(':currency_rate', $bcvValue);
            $queryUpdate->bindParam(':id', $row['id']);
            $queryUpdate->execute();
        } else {
            // Si no existe el registro para mañana, insertar un nuevo valor
            $queryInsert = $db->prepare("INSERT INTO `ospos_currencytable` (currency_rate, currency_symbol, currency_date) VALUES (:currency_rate, :currency_symbol, :currency_date)");
            $queryInsert->bindParam(':currency_rate', $bcvValue);
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
            // Si el valor de hoy existe, actualizar `ospos_app_config` con el valor de hoy para `currency_rate`
            $todayRate = $rowToday['currency_rate'];
            $updateConfig = $db->prepare("UPDATE `ospos_app_config` SET `value` = :dolarValue WHERE `key` = 'currency_rate'");
            $updateConfig->bindParam(':dolarValue', $todayRate);
            $updateConfig->execute();
        } else {
            // Si no se encontró el valor de hoy, notificar error (opcional)
            echo json_encode(['error' => "No se encontró el valor de la tasa de cambio para hoy en 'ospos_currencytable'."]);
            return;
        }

        // Paso 3: Actualizar `currency_rate_alternative` con el valor altValue
        $updateAlt = $db->prepare("UPDATE `ospos_app_config` SET `value` = :dolarValue WHERE `key` = 'currency_rate_alternative'");
        $updateAlt->bindParam(':dolarValue', $altValue);
        $updateAlt->execute();

        echo json_encode(['success' => 'Valores de dólar actualizados correctamente en la base de datos.']);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al insertar o actualizar la tasa de cambio: ' . $e->getMessage()]);
        return;
    }
} else {
    echo json_encode(['error' => 'Método no permitido o función no especificada.']);
}
