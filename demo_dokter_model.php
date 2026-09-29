<?php

include_once("bootstrap.php");

$db = new DBconnection();

$model = new DokterModel($db);

echo get_class($model);
echo " → tabel: ";
echo $model->nama_tabel();

echo "<br><br>";

print_r($model->find_all());

$db->close_connection();
