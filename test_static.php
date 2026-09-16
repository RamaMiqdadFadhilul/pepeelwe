<?php

include_once("bootstrap.php");

echo "<h1>Test Static Method</h1>";

echo "<h2>Konfigurasi</h2>";

echo "<p>";
echo "APP_NAME: ";
echo Konfigurasi::APP_NAME;
echo "</p>";

echo "<p>";
echo "VERSI: ";
echo Konfigurasi::VERSI;
echo "</p>";

echo "<p>";
echo "FILE_LOG: ";
echo Konfigurasi::FILE_LOG;
echo "</p>";


echo "<h2>Log</h2>";

Log::catat(
    "TEST",
    [
        "keterangan" => "Pengujian static method"
    ]
);

echo "<p>";
echo "Log berhasil dicatat.";
echo "</p>";

echo "<p>";
echo "Jumlah baris log pada request ini: ";
echo Log::jumlah_baris();
echo "</p>";