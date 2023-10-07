<?php
header('Content-Type: application/json');

$aResult = array();

if (!isset($_POST['functionname'])) {
    $aResult['error'] = 'No function name!';
} else {
    // Comprueba si la función solicitada existe.
    if (!function_exists($_POST['functionname'])) {
        $aResult['error'] = 'Function ' . $_POST['functionname'] . ' does not exist!';
    } else {
        switch ($_POST['functionname']) {
            case 'getDolar':
                $valor = file_get_contents('https://www.bcv.org.ve/');
                
                $pattern = '/<div id="dolar".*?<strong>(.*?)<\/strong>/s';
                if (preg_match($pattern, $valor, $matches)) {
                    $aResult['result'] = $matches[1];
                } else {
                    $aResult['error'] = 'No se encontró el campo en la página web.';
                }
                break;

            default:
                $aResult['error'] = 'Not found function ' . $_POST['functionname'] . '!';
                break;
        }
    }
}

echo json_encode($aResult);
?>