<?php

include_once("bootstrap.php");

$user = new User(
    1,
    "Budi",
    "budi@gmail.com"
);

$pemilik = new Pemilik(
    2,
    "Siti",
    "siti@gmail.com",
    "081234567890",
    "Jl. Airlangga 4"
);

$daftar_user = [
    $user,
    $pemilik
];

foreach ($daftar_user as $data) {
    echo $data . "<br>";
}

echo "<hr>";

$db = new DBconnection();

$model_user = new UserModel($db);
$model_role = new RoleModel($db);
$model_pemilik = new PemilikModel($db);

$daftar_model = [
    $model_user,
    $model_role,
    $model_pemilik
];

foreach ($daftar_model as $model) {
    echo get_class($model);
    echo " → tabel: ";
    echo $model->nama_tabel();
    echo "<br>";
}

$db->close_connection();
