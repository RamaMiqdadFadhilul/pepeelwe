<?php

include_once("demo/hewan.php");

$garong = new Kucing(
    "Garong",
    50,
    0
);

if ($garong->makan(20)) {
    echo "Garong maem sebanyak 20 kalori.<br>";
} else {
    echo "Garong kenyang.<br>";
}

$garong->lari(10);

$garong->lompat(5);

echo "Tingkat lapar Garong: "
    . $garong->get_tingkat_lapar()
    . "<br>";

$garong->bersuara();
