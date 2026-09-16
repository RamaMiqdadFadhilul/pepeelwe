<?php

include_once("bootstrap.php");

$email = strtolower(
    trim($_POST['username'] ?? '')
);

$password = $_POST['password'] ?? '';

$ip = $_SERVER['REMOTE_ADDR'] ?? '-';


try {

    $db = new DBconnection();

    $userModel = new UserModel($db);


    /*
    |--------------------------------------------------------------------------
    | Verifikasi login
    |--------------------------------------------------------------------------
    */

    $user = $userModel->verifikasi(
        $email,
        $password
    );


    /*
    |--------------------------------------------------------------------------
    | Login gagal
    |--------------------------------------------------------------------------
    */

    if ($user === null) {

        $db->close_connection();

        Log::catat(
            "LOGIN",
            [
                "email" => $email,
                "status" => "GAGAL",
                "ip" => $ip
            ]
        );

        Flash::set(
            "Email atau password tidak sesuai."
        );

        header(
            "Location: login.php"
        );

        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Login berhasil
    |--------------------------------------------------------------------------
    */

    $db->close_connection();


    if (
        session_status() ===
        PHP_SESSION_NONE
    ) {
        session_start();
    }


    /*
    |--------------------------------------------------------------------------
    | Simpan user ke session
    |--------------------------------------------------------------------------
    */

    $_SESSION['user'] =
        $user->get_user();


    /*
    |--------------------------------------------------------------------------
    | Simpan jenis object
    |--------------------------------------------------------------------------
    */

    $_SESSION['jenis_user'] =
        get_class($user);


    /*
    |--------------------------------------------------------------------------
    | Catat aktivitas
    |--------------------------------------------------------------------------
    */

    Log::catat(
        "LOGIN",
        [
            "email" => $email,
            "status" => "SUKSES",
            "ip" => $ip
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Redirect dashboard
    |--------------------------------------------------------------------------
    */

    header(
        "Location: dashboard.php"
    );

    exit();


} catch (DatabaseException $e) {

    Flash::set(
        "Kesalahan database: " .
        $e->getMessage()
    );

    header(
        "Location: login.php"
    );

    exit();
}
