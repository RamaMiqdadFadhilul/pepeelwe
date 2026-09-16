<?php

include_once("bootstrap.php");

echo "<h1>Test Notification</h1>";

try {

    // GANTI dengan iduser yang memang ada
    $userId = 1;


    // ==========================
    // CREATE
    // ==========================

    echo "<h2>1. Create</h2>";

    $notification = Notification::create(
        $userId,
        "Notifikasi Test",
        "Ini adalah notifikasi untuk pengujian."
    );

    echo "<p>";
    echo "Notification berhasil dibuat.";
    echo "</p>";

    echo "<pre>";

    print_r([
        'id' =>
            $notification->getId(),

        'userId' =>
            $notification->getUserId(),

        'title' =>
            $notification->getTitle(),

        'body' =>
            $notification->getBody(),

        'isRead' =>
            $notification->isRead(),

        'createdAt' =>
            $notification->getCreatedAt()
    ]);

    echo "</pre>";


    // ==========================
    // FIND
    // ==========================

    echo "<h2>2. Find</h2>";

    $found = Notification::find(
        $notification->getId()
    );

    if ($found !== null) {

        echo "<p>";
        echo "Notification ditemukan.";
        echo "</p>";

        echo htmlspecialchars(
            (string) $found
        );
    }


    // ==========================
    // UPDATE
    // ==========================

    echo "<h2>3. Update</h2>";

    $notification->update(
        "Judul Setelah Update",
        "Isi notification setelah update."
    );

    echo "<p>";
    echo "Notification berhasil di-update.";
    echo "</p>";


    // ==========================
    // FIND BY USER
    // ==========================

    echo "<h2>4. Find By User</h2>";

    $list =
        Notification::findByUser(
            $userId
        );

    echo "<p>";
    echo "Jumlah notification: ";
    echo count($list);
    echo "</p>";


    foreach ($list as $item) {

        echo "<p>";

        echo htmlspecialchars(
            (string) $item
        );

        echo "</p>";
    }


    // ==========================
    // UNREAD
    // ==========================

    echo "<h2>5. Unread</h2>";

    $unread =
        Notification::unread($list);

    echo "<p>";

    echo "Jumlah unread: ";
    echo count($unread);

    echo "</p>";


    // ==========================
    // COUNT UNREAD
    // ==========================

    echo "<h2>6. Count Unread</h2>";

    echo "<p>";

    echo Notification::countUnread(
        $list
    );

    echo "</p>";


    // ==========================
    // MARK AS READ
    // ==========================

    echo "<h2>7. Mark As Read</h2>";

    $notification->markAsRead();

    echo "<p>";

    echo "Status sekarang: ";

    echo $notification->isRead()
        ? "READ"
        : "UNREAD";

    echo "</p>";


    // ==========================
    // DELETE
    // ==========================

    echo "<h2>8. Delete</h2>";

    $notification->delete();

    echo "<p>";
    echo "Notification berhasil dihapus.";
    echo "</p>";


} catch (DatabaseException $e) {

    echo "<p style='color:red;'>";

    echo "DatabaseException: ";

    echo htmlspecialchars(
        $e->getMessage()
    );

    echo "</p>";
}