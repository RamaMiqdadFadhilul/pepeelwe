<?php

include_once("bootstrap.php");

// session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {

    Flash::set(
        "Silakan login terlebih dahulu."
    );

    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

$userId = (int) $user['iduser'];

// proses notifikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            // create
            case 'create':
                $title = trim(
                    $_POST['title'] ?? ''
                );
                $body = trim(
                    $_POST['body'] ?? ''
                );
                if (
                    $title === '' ||
                    $body === ''
                ) {
                    Flash::set(
                        "Judul dan isi wajib diisi."
                    );
                    break;
                }
                Notification::create(
                    $userId,
                    $title,
                    $body
                );
                Flash::set(
                    "Notifikasi berhasil dibuat."
                );
                break;

                // update
            case 'update':
                $id = (int) (
                    $_POST['id'] ?? 0
                );
                $title = trim(
                    $_POST['title'] ?? ''
                );
                $body = trim(
                    $_POST['body'] ?? ''
                );
                if (
                    $id <= 0 ||
                    $title === '' ||
                    $body === ''
                ) {
                    Flash::set(
                        "Data update tidak valid."
                    );
                    break;
                }
                $notification =
                    Notification::find($id);
                if ($notification === null) {
                    Flash::set(
                        "Notifikasi tidak ditemukan."
                    );
                    break;
                }
                // Pastikan notifikasi milik user
                if (
                    $notification->getUserId()
                    !== $userId
                ) {
                    Flash::set(
                        "Anda tidak memiliki akses ke notifikasi ini."
                    );

                    break;
                }
                $notification->update(
                    $title,
                    $body
                );

                Flash::set(
                    "Notifikasi berhasil diubah."
                );
                break;

                // read
            case 'read':

                $id = (int) (
                    $_POST['id'] ?? 0
                );

                $notification =
                    Notification::find($id);

                if ($notification === null) {

                    Flash::set(
                        "Notifikasi tidak ditemukan."
                    );

                    break;
                }

                if (
                    $notification->getUserId()
                    !== $userId
                ) {
                    Flash::set(
                        "Anda tidak memiliki akses ke notifikasi ini."
                    );

                    break;
                }

                $notification->markAsRead();

                Flash::set(
                    "Notifikasi ditandai sebagai sudah dibaca."
                );

                break;

                // delete

            case 'delete':
                $id = (int) (
                    $_POST['id'] ?? 0
                );

                $notification =
                    Notification::find($id);

                if ($notification === null) {

                    Flash::set(
                        "Notifikasi tidak ditemukan."
                    );

                    break;
                }

                if (
                    $notification->getUserId()
                    !== $userId
                ) {
                    Flash::set(
                        "Anda tidak memiliki akses ke notifikasi ini."
                    );
                    break;
                }

                $notification->delete();

                Flash::set(
                    "Notifikasi berhasil dihapus."
                );

                break;
            default:

                Flash::set(
                    "Action tidak dikenal."
                );

                break;
        }

    } catch (DatabaseException $e) {

        Flash::set(
            "Kesalahan database: " .
            $e->getMessage()
        );
    }

    // Setelah POST kembali ke dashboard
    header("Location: dashboard.php");
    exit();
}


// get data notif

try {

    $notifications =
        Notification::findByUser(
            $userId
        );

    $unread =
        Notification::unread(
            $notifications
        );

    $unreadCount =
        Notification::countUnread(
            $notifications
        );

} catch (DatabaseException $e) {

    $notifications = [];

    $unread = [];

    $unreadCount = 0;

    $error =
        $e->getMessage();
}


//log

Log::catat(
    "AKSES",
    [
        "email" =>
            $user['email'],

        "method" =>
            $_SERVER['REQUEST_METHOD'],

        "url" =>
            $_SERVER['REQUEST_URI']
    ]
);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
<!-- header -->
<h1>
    Selamat datang,
    <?= htmlspecialchars(
        $user['nama']
    ) ?>
</h1>

<p>
    Email:
    <strong>
        <?= htmlspecialchars(
            $user['email']
        ) ?>
    </strong>
</p>

<p>
    Role aktif:
    <strong>
        <?= htmlspecialchars(
            $user['role']
        ) ?>
    </strong>
</p>

<p>
    Jenis user:
    <strong>
        <?= htmlspecialchars(
            $_SESSION['jenis_user'] ?? 'User'
        ) ?>
    </strong>
</p>

<?php if (
    isset($user['no_wa']) &&
    isset($user['alamat'])
): ?>

    <p>
        No. WhatsApp:
        <strong>
            <?= htmlspecialchars(
                $user['no_wa']
            ) ?>
        </strong>
    </p>

    <p>
        Alamat:
        <strong>
            <?= htmlspecialchars(
                $user['alamat']
            ) ?>
        </strong>
    </p>

<?php endif; ?>

<p>
    <?= Konfigurasi::APP_NAME ?>
    -
    Versi <?= Konfigurasi::VERSI ?>
</p>

<hr>
<!-- flash -->
<?php Flash::tampilkan(); ?>

<?php if (isset($error)): ?>
    <p style="color:red;">
        <?= htmlspecialchars(
            $error
        ) ?>

    </p>
<?php endif; ?>

<!-- notif summary-->
<h2>
    Notifikasi
</h2>

<p>
    Jumlah belum dibaca:
    <strong>
        <?= $unreadCount ?>
    </strong>
</p>

<hr>
<!-- buat notif-->
<h3>
    Buat Notifikasi
</h3>

<form
    method="POST"
    action="dashboard.php"
>
    <input
        type="hidden"
        name="action"
        value="create"
    >
    <label>
        Judul
    </label>
    <br>
    <input
        type="text"
        name="title"
        maxlength="150"
        required
    >
    <br><br>
    <label>
        Isi Notifikasi
    </label>
    <br>
    <textarea
        name="body"
        rows="5"
        cols="50"
        required
    ></textarea>
    <br><br>
    <button type="submit">
        Buat Notifikasi
    </button>
</form>
<hr>
<!--daftar notifikasi-->
<h3>
    Daftar Notifikasi
</h3>
<?php if (
    count($notifications) === 0
): ?>
    <p>
        Belum ada notifikasi.
    </p>
<?php else: ?>
    <?php foreach (
        $notifications as $notification
    ): ?>
        <div
            style="
                border: 1px solid #ccc;
                padding: 15px;
                margin-bottom: 15px;
            "
        >
            <!-- Judul -->
            <h4>
                <?= htmlspecialchars(
                    $notification->getTitle()
                ) ?>
            </h4>
            <!-- Isi -->
            <p>
                <?= nl2br(
                    htmlspecialchars(
                        $notification->getBody()
                    )
                ) ?>
            </p>
            <!-- Informasi -->
            <p>
                ID:
                <?= $notification->getId() ?>
                <br>
                Dibuat:
                <?= htmlspecialchars(
                    $notification->getCreatedAt()
                ) ?>
                <br>
                Status:
                <strong>
                    <?php if (
                        $notification->isRead()
                    ): ?>
                        READ
                    <?php else: ?>
                        UNREAD
                    <?php endif; ?>
                </strong>
            </p>
            <!-- tandai dibaca-->
            <?php if (
                !$notification->isRead()
            ): ?>
                <form
                    method="POST"
                    action="dashboard.php"
                    style="display:inline;"
                >
                    <input
                        type="hidden"
                        name="action"
                        value="read"
                    >
                    <input
                        type="hidden"
                        name="id"
                        value="<?= $notification->getId() ?>"
                    >
                    <button type="submit">
                        Tandai Dibaca
                    </button>
                </form>
            <?php endif; ?>


            <!-- hapus -->

            <form
                method="POST"
                action="dashboard.php"
                style="display:inline;"
            >


                <input
                    type="hidden"
                    name="action"
                    value="delete"
                >


                <input
                    type="hidden"
                    name="id"
                    value="<?= $notification->getId() ?>"
                >


                <button type="submit">

                    Hapus

                </button>


            </form>


            <br><br>


            <!-- apdet-->

            <details>


                <summary>
                    Edit Notifikasi
                </summary>


                <br>


                <form
                    method="POST"
                    action="dashboard.php"
                >


                    <input
                        type="hidden"
                        name="action"
                        value="update"
                    >


                    <input
                        type="hidden"
                        name="id"
                        value="<?= $notification->getId() ?>"
                    >


                    <label>
                        Judul
                    </label>

                    <br>


                    <input
                        type="text"
                        name="title"
                        maxlength="150"
                        value="<?= htmlspecialchars(
                            $notification->getTitle()
                        ) ?>"
                        required
                    >


                    <br><br>


                    <label>
                        Isi
                    </label>

                    <br>


                    <textarea
                        name="body"
                        rows="5"
                        cols="50"
                        required
                    ><?= htmlspecialchars(
                        $notification->getBody()
                    ) ?></textarea>


                    <br><br>


                    <button type="submit">

                        Simpan Perubahan

                    </button>


                </form>


            </details>


        </div>


    <?php endforeach; ?>


<?php endif; ?>


<hr>


<!-- logout -->

<a href="logout.php">
    Logout
</a>

</body>
</html>