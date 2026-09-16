<?php

include_once("bootstrap.php");

echo "<h1>Test DB Connection</h1>";

try {

    $db = new DBconnection();

    echo "<p style='color:green;'>";
    echo "Koneksi database berhasil.";
    echo "</p>";

    $respon = $db->send_query(
        "SELECT current_database() AS database_name"
    );

    if ($respon->status) {

        echo "<p>";
        echo "Database: ";

        echo htmlspecialchars(
            $respon->data[0]['database_name']
        );

        echo "</p>";

    } else {

        echo "<p style='color:red;'>";
        echo "Query gagal: ";
        echo htmlspecialchars(
            $respon->message
        );
        echo "</p>";
    }

    $db->close_connection();

} catch (DatabaseException $e) {

    echo "<p style='color:red;'>";
    echo "DatabaseException: ";
    echo htmlspecialchars(
        $e->getMessage()
    );
    echo "</p>";
}