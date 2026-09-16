<?php

include_once("bootstrap.php");

$db = new DBconnection();

$model = new UserModel($db);

$data = $model->find_all();

echo "<pre>";
print_r($data);
echo "</pre>";

$db->close_connection();
