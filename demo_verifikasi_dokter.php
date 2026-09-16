<?php

include_once("bootstrap.php");

$db = new DBconnection();

$userModel = new UserModel($db);

$user = $userModel->verifikasi(
    "budi@gmail.com",
    "12345678"
);

if ($user === null) {
    echo "Login gagal.";
} else {
    echo "Class object: ";
    echo get_class($user);

    echo "<br><br>";

    echo "Data:";

    echo "<pre>";
    print_r($user->get_user());
    echo "</pre>";
}

$db->close_connection();
