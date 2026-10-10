
<?php

abstract class Laporan
{
    protected string $judul;

    public function __construct(string $judul)
    {
        $this->judul = $judul;
    }

    public function header(): void
    {
        echo "<h2>{$this->judul}</h2>";
        echo "<hr>";
    }

    abstract public function isi(): void;

    public function footer(): void
    {
        echo "<hr>";
        echo "<p>Laporan selesai.</p>";
    }

    public function cetak(): void
    {
        $this->header();
        $this->isi();
        $this->footer();
    }
}
