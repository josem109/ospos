<?php
// Obtiene la ruta del archivo actual
$current_file_path = __FILE__;

// Obtiene la ruta de la carpeta log al mismo nivel del archivo actual
$log_folder_path = dirname($current_file_path) . '/log';

// Define el nombre y la ubicación del archivo de registro
$log_file = $log_folder_path . '/archivo.log';

// Habilita el registro de errores en PHP
ini_set('log_errors', 1);
ini_set('error_log', $log_file);
// Define las variables de conexión a las bases de datos locales y remotas
$host_local = "localhost";
$username_local = "root";
$password_local = "";
$database_local = "ospos";

$host_remote = "172.67.130.187";
$username_remote = "integracion";
$password_remote = "8xwFs18?1";
$database_remote = "integracion";

// Conecta a la base de datos local
$connection_local = new PDO("mysql:host=$host_local;dbname=$database_local", $username_local, $password_local);
$connection_local->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Consulta la máxima fecha de la tabla control_integraciones
$statement_date = $connection_local->prepare("SELECT MAX(last_run) AS max_date FROM control_integraciones");
$statement_date->execute();
$max_date = $statement_date->fetchColumn();

// Realiza la consulta a la base de datos local filtrando por la fecha máxima
$statement_local = $connection_local->prepare("SELECT name,
category,
supplier_id,
item_number,
description,
cost_price,
unit_price,
reorder_level,
receiving_quantity,
item_id,
pic_filename,
allow_alt_description,
is_serialized,
stock_type,
item_type,
deleted,
tax_category_id,
qty_per_pack,
pack_name,
low_sell_item_id,
hsn_code,
quantity,
location_id,
last_updated
FROM integracion
WHERE last_updated > :max_date");
$statement_local->bindParam(':max_date', $max_date);
$statement_local->execute();
$result_local = $statement_local->fetchAll();

// Escribe los resultados en el archivo de registro
error_log(print_r($result_local, true));

// Conecta a la base de datos remota
$connection_remote = new PDO("mysql:host=$host_remote;dbname=$database_remote", $username_remote, $password_remote);
$connection_remote->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Borra la tabla ospos_items en la base de datos remota
//$statement_remote = $connection_remote->prepare("DELETE FROM ospos_items");
//$statement_remote->execute();

// Inserta los valores de la consulta anterior en la tabla ospos_items en la base de datos remota
// ... Código anterior ...

foreach ($result_local as $row) {
  // Declarar $now antes de usarlo en la consulta
  $now = date("Y-m-d H:i:s"); // Fecha actual

  $statement_remote = $connection_remote->prepare("INSERT INTO integracion (
      name,
      category,
      supplier_id,
      item_number,
      description,
      cost_price,
      unit_price,
      reorder_level,
      receiving_quantity,
      item_id,
      pic_filename,
      allow_alt_description,
      is_serialized,
      stock_type,
      item_type,
      deleted,
      tax_category_id,
      qty_per_pack,
      pack_name,
      low_sell_item_id,
      hsn_code,
      quantity,
      location_id,
      last_updated
  ) VALUES (
      :name,
      :category,
      :supplier_id,
      :item_number,
      :description,
      :cost_price,
      :unit_price,
      :reorder_level,
      :receiving_quantity,
      :item_id,
      :pic_filename,
      :allow_alt_description,
      :is_serialized,
      :stock_type,
      :item_type,
      :deleted,
      :tax_category_id,
      :qty_per_pack,
      :pack_name,
      :low_sell_item_id,
      :hsn_code,
      :quantity,
      1,
      :last_updated
  )");

  $statement_remote->bindParam(":name", $row['name']);
  $statement_remote->bindParam(":category", $row['category']);
  $statement_remote->bindParam(":supplier_id", $row['supplier_id']);
  $statement_remote->bindParam(":item_number", $row['item_number']);
  $statement_remote->bindParam(":description", $row['description']);
  $statement_remote->bindParam(":cost_price", $row['cost_price']);
  $statement_remote->bindParam(":unit_price", $row['unit_price']);
  $statement_remote->bindParam(":reorder_level", $row['reorder_level']);
  $statement_remote->bindParam(":receiving_quantity", $row['receiving_quantity']);
  $statement_remote->bindParam(":item_id", $row['item_id']);
  $statement_remote->bindParam(":pic_filename", $row['pic_filename']);
  $statement_remote->bindParam(":allow_alt_description", $row['allow_alt_description']);
  $statement_remote->bindParam(":is_serialized", $row['is_serialized']);
  $statement_remote->bindParam(":stock_type", $row['stock_type']);
  $statement_remote->bindParam(":item_type", $row['item_type']);
  $statement_remote->bindParam(":deleted", $row['deleted']);
  $statement_remote->bindParam(":tax_category_id", $row['tax_category_id']);
  $statement_remote->bindParam(":qty_per_pack", $row['qty_per_pack']);
  $statement_remote->bindParam(":pack_name", $row['pack_name']);
  $statement_remote->bindParam(":low_sell_item_id", $row['low_sell_item_id']);
  $statement_remote->bindParam(":hsn_code", $row['hsn_code']);
  $statement_remote->bindParam(":quantity", $row['quantity']);
  $statement_remote->bindParam(":last_updated", $now);

  $statement_remote->execute();

// Inserta el timestamp actual en la tabla control_migraciones
$statement_insert = $connection_local->prepare("INSERT INTO control_integraciones (last_run) VALUES (NOW())");
$statement_insert->execute();
  // Cierra la conexión a la base de datos local
$connection_local = null;
}

// ... Resto de tu código ...


?>