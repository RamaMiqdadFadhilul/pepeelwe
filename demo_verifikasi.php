<?php

include_once("bootstrap.php");

try {
    $db = new DBconnection();

    $userModel = new UserModel($db);

    $user = $userModel->verifikasi(
        "admin@gmail.com",
        "admin123"
    );

    if ($user === null) {
        echo "Login gagal.";
    } else {
        echo "Class object: "
            . get_class($user)
            . "<br>";

        echo "Data user:<br>";

        print_r($user->get_user());
    }

    $db->close_connection();

} catch (DatabaseException $e) {
    echo "Kesalahan database: "
        . $e->getMessage();
}
