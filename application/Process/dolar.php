<?php
$url = 'https://www.bcv.org.ve/';  // Reemplaza esta URL con la URL de la página web que deseas explorar

// Obtener el contenido de la página web
$html = file_get_contents($url);

// Buscar el valor del campo utilizando una expresión regular
$pattern = '/<div id="dolar".*?<strong>(.*?)<\/strong>/s';
if (preg_match($pattern, $html, $matches)) {
    $valor = $matches[1];
    echo 'El valor del campo es: ' . $valor;
} else {
    echo 'No se encontró el campo en la página web.';
}
?>
