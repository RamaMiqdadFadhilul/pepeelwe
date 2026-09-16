<?php

include_once("bootstrap.php");

try {
    $db = new DBconnection();

    $models = [
        new UserModel($db),
        new RoleModel($db),
        new PemilikModel($db)
    ];

    foreach ($models as $model) {
        echo get_class($model)
            . " -> tabel "
            . $model->nama_tabel()
            . " -> "
            . count($model->find_all())
            . " baris<br>";
    }

    $db->close_connection();

} catch (DatabaseException $e) {
    echo "Kesalahan database: "
        . $e->getMessage();
}
