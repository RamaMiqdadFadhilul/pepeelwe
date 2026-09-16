<?php

include_once("bootstrap.php");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Login</title>

</head>

<body>

<h1>Login</h1>

<?php Flash::tampilkan(); ?>

<form
    action="login_post.php"
    method="POST"
>

    <label>Email</label>

    <br>

    <input
        type="email"
        name="username"
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

    <button type="submit">
        Login
    </button>

</form>

<p>

    Belum punya akun?

    <a href="registrasi.php">
        Registrasi
    </a>

</p>

</body>

</html>