<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $functionname = $_POST['functionname'];

    if ($functionname === 'getDolar') {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]; 
        $url = 'https://www.bcv.org.ve/';
        $html = file_get_contents($url, false, stream_context_create($arrContextOptions));
        $result ='';
        $pattern = '/<div id="dolar".*?<strong>(.*?)<\/strong>/s';
        $pattern2 = preg_match('/<span class="date-display-single" property="dc:date" datatype="xsd:dateTime" content="(.*?)">/', $html, $matches2);
        if (preg_match($pattern, $html, $matches)) {
            $valor = trim($matches[1]);
            $valor = str_replace(',', '.', $valor); // Cambia la coma por un punto
            $valor = round($valor, 2); // Redondea a dos decimales
            $valor2 = trim($matches2[1]);
            $fecha = date('Y-m-d', strtotime($valor2)); 
            $currency_symbol = 'USD';
            try {
                $db = new PDO('mysql:host=localhost;dbname=osposam', 'root', '');
                date_default_timezone_set('America/Caracas');
                $fecha_hoy = date('Y-m-d'); // Obtiene la fecha actual
                $query = $db->prepare("SELECT currency_rate FROM `ospos_currencytable` WHERE `currency_symbol` = :currency_symbol AND `currency_date` = :currency_date");
                $query->bindParam(':currency_symbol', $currency_symbol);
                $query->bindParam(':currency_date', $fecha_hoy);
                $query->execute();
                $filas_upd = $query->rowCount();
                if ($filas_upd > 0) {
                    $row = $query->fetch(PDO::FETCH_ASSOC);
                    $value = $row['currency_rate'];
                    $query = $db->prepare("UPDATE `ospos_app_config` SET `value` = :dolarValue WHERE `key` = 'currency_rate'");
                    $query->bindParam(':dolarValue', $value);
                    $query->execute();
                }

                // Preparar la sentencia SQL para verificar si ya existen los mismos valores
                $query2 = $db->prepare("SELECT * FROM `ospos_currencytable` WHERE `currency_symbol` = :currency_symbol AND `currency_date` = :currency_date");
                $query2->bindParam(':currency_symbol', $currency_symbol);
                $query2->bindParam(':currency_date', $fecha);
                // Ejecutar la sentencia
                $query2->execute();

               $filas = $query2->rowCount();
                // Si no se encontraron registros, entonces insertar el nuevo registro
                if ($filas == 0) {
                    
                    $query = $db->prepare("INSERT INTO `ospos_currencytable` (currency_rate, currency_symbol, currency_date) VALUES (:currency_rate, :currency_symbol, :currency_date)");
                    $query->bindParam(':currency_rate', $valor);
                    $query->bindParam(':currency_symbol', $currency_symbol);
                    $query->bindParam(':currency_date', $fecha);
                    if ($query->execute()) {
                        //$result = 'insert';
                    }else{
                        $error = $query->errorInfo();
                        $result = "La consulta no se pudo ejecutar. Error: " . $error[2];
                        
                    } 
                }        
                $db = null; // Cerrar la conexión después de la ejecución
                } catch (PDOException $e) {
                echo json_encode(['error' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
                return;
                }

            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type');
            echo json_encode(['result' =>  $valor . " fecha: " . $fecha]);
        } else {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type');
            echo json_encode(['error' => 'No se encontró el campo en la página web.']);
        }
    }
}
?>
