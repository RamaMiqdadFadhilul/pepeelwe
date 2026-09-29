<?php

include_once("bootstrap.php");

$db = new DBconnection();

$userModel = new UserModel($db);
$dokterModel = new DokterModel($db);
$pemilikModel = new PemilikModel($db);

// Ambil semua user
$data_user = $userModel->find_all();

// Ambil role aktif
$respon_role = $db->send_query(
    'SELECT ur.iduser, r.nama_role
     FROM user_role ur
     JOIN role r ON r.idrole = ur.idrole
     WHERE ur.status = TRUE
     ORDER BY ur.iduser'
);

$role_aktif = [];

foreach ($respon_role->data as $row) {
    $role_aktif[(int) $row['iduser']] = $row['nama_role'];
}


// Ubah data user menjadi object sesuai jenisnya
$daftar_objek = [];

foreach ($data_user as $row) {

    $iduser = (int) $row['iduser'];

    // Cek apakah user adalah Dokter
    $dokter = $dokterModel->find_by_iduser($iduser);

    // Cek apakah user adalah Pemilik
    $pemilik = $pemilikModel->find_by_iduser($iduser);

    if ($dokter !== null) {

        $objek = new Dokter(
            $iduser,
            $row['nama'],
            $row['email'],
            $dokter['no_izin'],
            $dokter['spesialisasi']
        );

    } elseif ($pemilik !== null) {

        $objek = new Pemilik(
            $iduser,
            $row['nama'],
            $row['email'],
            $pemilik['no_wa'],
            $pemilik['alamat']
        );

    } else {

        $objek = new User(
            $iduser,
            $row['nama'],
            $row['email']
        );
    }

    $daftar_objek[] = $objek;
}


// Tampilan
echo "<h1>Daftar User</h1>";

echo "<table border='1' cellpadding='8' cellspacing='0'>";

echo "<tr>";
echo "<th>Nama</th>";
echo "<th>Email</th>";
echo "<th>Role Aktif</th>";
echo "<th>Jenis User</th>";
echo "<th>Status Pemilik</th>";
echo "</tr>";


// Satu loop untuk semua jenis object
foreach ($daftar_objek as $user) {

    $data = $user->get_user();

    $iduser = $data['iduser'];

    $role = $role_aktif[$iduser] ?? '-';

    $status_pemilik = $user instanceof Pemilik
        ? 'Ya'
        : 'Tidak';

    echo "<tr>";

    echo "<td>"
        . htmlspecialchars($data['nama'])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($data['email'])
        . "</td>";

    echo "<td>"
        . htmlspecialchars($role)
        . "</td>";

    echo "<td>"
        . htmlspecialchars(get_class($user))
        . "</td>";

    echo "<td>"
        . $status_pemilik
        . "</td>";

    echo "</tr>";
}

echo "</table>";

$db->close_connection();
