<?php

include_once("bootstrap.php");

echo "<h1>Test Constructor dan Destructor</h1>";

try {

    $db = new DBconnection();

    echo "<p>";
    echo "Object DBconnection berhasil dibuat.";
    echo "</p>";

    $db->close_connection();

    echo "<p>";
    echo "Koneksi berhasil ditutup.";
    echo "</p>";

} catch (DatabaseException $e) {

    echo "<p style='color:red;'>";
    echo htmlspecialchars(
        $e->getMessage()
    );
    echo "</p>";
}


// Test Role

$role = new Role(
    1,
    "Admin",
    true
);

echo "<h2>Test Role</h2>";

echo "<pre>";

print_r(
    $role->get_data()
);

echo "</pre>";


// Test User

$user = new User(
    1,
    "Rama",
    "RAMA@EMAIL.COM"
);

$user->set_role($role);

echo "<h2>Test User</h2>";

echo "<pre>";

print_r(
    $user->get_user()
);

echo "</pre>";

echo "<p>";

echo "String User: ";

echo htmlspecialchars(
    (string) $user
);

echo "</p>";