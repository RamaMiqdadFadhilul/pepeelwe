
<?php

include_once("bootstrap.php");

try {
    $db = new DBconnection();

    $userModel = new UserModel($db);
    $pemilikModel = new PemilikModel($db);

    $daftar_laporan = [
        new LaporanUser($userModel),
        new LaporanPemilik($pemilikModel)
    ];

    foreach ($daftar_laporan as $laporan) {
        $laporan->cetak();
        echo "<br>";
    }

    $db->close_connection();

} catch (DatabaseException $e) {
    echo "Kesalahan database: " .
        htmlspecialchars($e->getMessage());
}
