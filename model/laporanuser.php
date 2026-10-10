
<?php

class LaporanUser extends Laporan
{
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        parent::__construct("Laporan Data User");
        $this->userModel = $userModel;
    }

    public function isi(): void
    {
        $users = $this->userModel->find_all();

        if (empty($users)) {
            echo "<p>Data user tidak tersedia.</p>";
            return;
        }

        echo "<table border='1' cellpadding='8'>";
        echo "<tr>";

        foreach (array_keys($users[0]) as $kolom) {
            echo "<th>" .
                htmlspecialchars((string) $kolom) .
                "</th>";
        }

        echo "</tr>";

        foreach ($users as $user) {
            echo "<tr>";

            foreach ($user as $nilai) {
                echo "<td>" .
                    htmlspecialchars((string) ($nilai ?? "")) .
                    "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    }
}
