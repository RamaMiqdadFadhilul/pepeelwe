<?php

include_once("bootstrap.php");

try {

    $daftar_role =
        Role::get_opsi_role();

} catch (DatabaseException $e) {

    $daftar_role = [];

    $error_role =
        $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Registrasi</title>

</head>

<body>

<h1>Registrasi</h1>

<?php Flash::tampilkan(); ?>

<?php if (isset($error_role)): ?>

    <p style="color:red;">

        <?= htmlspecialchars(
            $error_role
        ) ?>

    </p>

<?php endif; ?>


<form
    action="proses_registrasi.php"
    method="POST"
>

    <label>Nama</label>

    <br>

    <input
        type="text"
        name="nama"
        required
    >

    <br><br>


    <label>Email</label>

    <br>

    <input
        type="email"
        name="email"
        required
    >

    <br><br>


    <label>Password</label>

    <br>

    <input
        type="password"
        name="password"
        required
    >

    <br><br>


    <label>Retype Password</label>

    <br>

    <input
        type="password"
        name="retype_password"
        required
    >

    <br><br>

    <label>No. WhatsApp</label>

    <br>

    <input
        type="text"
        name="no_wa"
    >

    <br><br>

    <label>Alamat</label>

    <br>

    <textarea
        name="alamat"
        rows="3"
    ></textarea>

    <br><br>


    <label>Role</label>

    <br>

    <select
        name="idrole"
        required
    >

        <?php foreach (
            $daftar_role as $role
        ): ?>

            <?php
            $data =
                $role->get_data();
            ?>

            <option
                value="<?= $data['idrole'] ?>"
            >

                <?= htmlspecialchars(
                    $data['nama_role']
                ) ?>

            </option>

        <?php endforeach; ?>

    </select>

    <br><br>


    <button type="submit">
        Registrasi
    </button>

</form>


<p>

    Sudah punya akun?

    <a href="login.php">
        Login
    </a>

</p>

</body>

</html>

