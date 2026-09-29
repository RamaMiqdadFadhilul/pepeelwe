<?php

include_once("bootstrap.php");

$dokter = new Dokter(
    3,
    "Dr. Budi",
    "budi@gmail.com",
    "SIP-12345",
    "Dokter Umum"
);

echo $dokter;

echo "<pre>";
print_r($dokter->get_user());
echo "</pre>";
