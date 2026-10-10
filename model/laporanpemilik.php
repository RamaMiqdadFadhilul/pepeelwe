
<?php

class LaporanPemilik extends Laporan
{
    private PemilikModel $pemilikModel;

    public function __construct(PemilikModel $pemilikModel)
    {
        parent::__construct("Laporan Data Pemilik");
        $this->pemilikModel = $pemilikModel;
    }

    public function isi(): void
    {
        $pemilik = $this->pemilikModel->find_all();

        if (empty($pemilik)) {
            echo "<p>Data pemilik tidak tersedia.</p>";
            return;
        }

        echo "<table border='1' cellpadding='8'>";
        echo "<tr>";

        foreach (array_keys($pemilik[0]) as $kolom) {
            echo "<th>" .
                htmlspecialchars((string) $kolom) .
                "</th>";
        }

        echo "</tr>";

        foreach ($pemilik as $data) {
            echo "<tr>";

            foreach ($data as $nilai) {
                echo "<td>" .
                    htmlspecialchars((string) ($nilai ?? "")) .
                    "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    }
}
