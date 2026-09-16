<?php

include_once("bootstrap.php");

$nama = trim(
    $_POST['nama'] ?? ''
);

$email = strtolower(
    trim($_POST['email'] ?? '')
);

$password = $_POST['password'] ?? '';

$retype = $_POST['retype_password'] ?? '';

$idrole = (int) (
    $_POST['idrole'] ?? 0
);

$no_wa = trim(
    $_POST['no_wa'] ?? ''
);

$alamat = trim(
    $_POST['alamat'] ?? ''
);


/*
|--------------------------------------------------------------------------
| Validasi password
|--------------------------------------------------------------------------
*/

if ($password !== $retype) {

    Flash::set(
        "Password dan Retype Password tidak cocok."
    );

    header(
        "Location: registrasi.php"
    );

    exit();
}


/*
|--------------------------------------------------------------------------
| Validasi data kosong
|--------------------------------------------------------------------------
*/

if (
    $nama === '' ||
    $email === '' ||
    $password === '' ||
    $idrole <= 0
) {

    Flash::set(
        "Semua data wajib diisi."
    );

    header(
        "Location: registrasi.php"
    );

    exit();
}


/*
|--------------------------------------------------------------------------
| Validasi email
|--------------------------------------------------------------------------
*/

if (!filter_var(
    $email,
    FILTER_VALIDATE_EMAIL
)) {

    Flash::set(
        "Format email tidak valid."
    );

    header(
        "Location: registrasi.php"
    );

    exit();
}


try {

    $db = new DBconnection();

    $userModel = new UserModel($db);

    $pemilikModel = new PemilikModel($db);


    /*
    |--------------------------------------------------------------------------
    | Cek apakah email sudah terdaftar
    |--------------------------------------------------------------------------
    */

    $userLama =
        $userModel->find_by_email($email);

    if ($userLama !== null) {

        $db->close_connection();

        Flash::set(
            "Email tersebut sudah terdaftar."
        );

        header(
            "Location: registrasi.php"
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Hash password
    |--------------------------------------------------------------------------
    */

    $hash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    /*
    |--------------------------------------------------------------------------
    | Insert user
    |--------------------------------------------------------------------------
    */

    $respon = $userModel->insert([
        'nama' => $nama,
        'email' => $email,
        'password' => $hash
    ]);


    if (!$respon->status) {

        throw new DatabaseException(
            $respon->message
        );
    }


    $iduser =
        (int) $respon->data[0]['iduser'];


    /*
    |--------------------------------------------------------------------------
    | Hubungkan user dengan role aktif
    |--------------------------------------------------------------------------
    */

    $roleRespon =
        $userModel->simpan_role_aktif(
            $iduser,
            $idrole
        );


    if (!$roleRespon->status) {

        throw new DatabaseException(
            $roleRespon->message
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Simpan data pemilik jika diisi
    |--------------------------------------------------------------------------
    */

    if (
        $no_wa !== '' &&
        $alamat !== ''
    ) {

        $pemilikRespon =
            $pemilikModel->insert([
                'no_wa' => $no_wa,
                'alamat' => $alamat,
                'iduser' => $iduser
            ]);

        if (!$pemilikRespon->status) {

            throw new DatabaseException(
                $pemilikRespon->message
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Catat aktivitas
    |--------------------------------------------------------------------------
    */

    Log::catat(
        "REGISTRASI",
        [
            "email" => $email,
            "iduser" => $iduser
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Berhasil
    |--------------------------------------------------------------------------
    */

    Flash::set(
        "Registrasi berhasil. Silakan login."
    );

    $db->close_connection();


} catch (DatabaseException $e) {

    Flash::set(
        "Kesalahan database: " .
        $e->getMessage()
    );
}


header(
    "Location: login.php"
);

exit();
