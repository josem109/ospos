<?php
// URL de la página web
$url = 'https://monitordolarvenezuela.com/monitor-dolar-hoy';

// Obtener el contenido HTML de la página
$html = file_get_contents($url);

// Introducir un retraso de 5 segundos después de obtener el HTML
sleep(5);

// Definir la expresión regular para capturar el valor específico
$pattern = '/<tr class="even:bg-gray-100 hover:font-bold  hover:bg-sky-100">.*?<td class="px-2 py-2 border-b border-gray-200  text-sm text-center">5 USD<\/td>.*?<td class="px-2 py-2 border-b border-gray-200  text-sm text-center">(.*?)<\/td>/s';

// Buscar el valor con la expresión regular
if (preg_match($pattern, $html, $matches)) {
    // Extraer el valor capturado
    $value = trim($matches[1]);
    echo "El valor extraído es: " . $value;
} else {
    echo "No se encontró el valor.";
}
?>
