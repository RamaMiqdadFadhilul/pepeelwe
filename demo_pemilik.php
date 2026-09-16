<?php

include_once("bootstrap.php");

$pemilik = new Pemilik(
    1,
    "Siti Aminah",
    "siti@mail.com",
    "081234567890",
    "Jl. Airlangga 4, Surabaya"
);

echo $pemilik;

echo "<br><br>";

print_r($pemilik->get_user());
