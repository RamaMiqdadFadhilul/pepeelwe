<?php

include_once("bootstrap.php");

echo "<h1>Daftar Role</h1>";

try {

    $roles = Role::get_opsi_role();

    if (count($roles) === 0) {

        echo "<p>";
        echo "Belum ada role.";
        echo "</p>";

    } else {

        echo "<table border='1' cellpadding='8'>";

        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Nama Role</th>";
        echo "<th>Status</th>";
        echo "</tr>";

        foreach ($roles as $role) {

            $data =
                $role->get_data();

            echo "<tr>";

            echo "<td>";
            echo $data['idrole'];
            echo "</td>";

            echo "<td>";
            echo htmlspecialchars(
                $data['nama_role']
            );
            echo "</td>";

            echo "<td>";

            echo $data['status']
                ? "Aktif"
                : "Tidak Aktif";

            echo "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

} catch (DatabaseException $e) {

    echo "<p style='color:red;'>";

    echo htmlspecialchars(
        $e->getMessage()
    );

    echo "</p>";
}