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

        $pattern = '/<div id="dolar".*?<strong>(.*?)<\/strong>/s';
        if (preg_match($pattern, $html, $matches)) {
            $valor = trim($matches[1]);
            $valor = str_replace(',', '.', $valor); // Cambia la coma por un punto
            //$valor = substr($valor, 0, strpos($valor, '.') + 5); // Reconoce solo los primeros cuatro 
            //$valor_formateado = number_format($valor, 2, '.', ',');

            try {
                $db = new PDO('mysql:host=localhost;dbname=ospos', 'root', '');
                $query = $db->prepare("UPDATE `ospos_app_config` SET `value` = :dolarValue WHERE `key` = 'currency_rate'");
                $query->bindParam(':dolarValue', $valor);
                $query->execute();
                $db = null; // Cerrar la conexión después de la ejecución
            } catch (PDOException $e) {
                echo json_encode(['error' => 'Error al conectar con la base de datos: ' . $e->getMessage()]);
                return;
            }

            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type');
            echo json_encode(['result' => $valor]);
        } else {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type');
            echo json_encode(['error' => 'No se encontró el campo en la página web.']);
        }
    }
}
?>
