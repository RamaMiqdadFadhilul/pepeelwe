<?php

abstract class Bidang
{
    private string $nama;
    private float $sisi_satu;
    private float $sisi_dua;

    public function __construct(
        string $nama,
        float $sisi_satu,
        float $sisi_dua
    ) {
        $this->nama = $nama;
        $this->sisi_satu = $sisi_satu;
        $this->sisi_dua = $sisi_dua;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getSisiSatu(): float
    {
        return $this->sisi_satu;
    }

    public function getSisiDua(): float
    {
        return $this->sisi_dua;
    }

    // Abstract method
    abstract public function luas(): void;

    abstract public function keliling(): void;
}


class PersegiPanjang extends Bidang
{
    public function __construct(
        string $nama,
        float $sisi_satu,
        float $sisi_dua
    ) {
        parent::__construct($nama, $sisi_satu, $sisi_dua);
    }

    public function luas(): void
    {
        $luas = $this->getSisiSatu() * $this->getSisiDua();

        echo "Luas persegi panjang {$this->getNama()}: $luas<br>";
    }

    public function keliling(): void
    {
        $keliling = 2 * (
            $this->getSisiSatu() +
            $this->getSisiDua()
        );

        echo "Keliling persegi panjang {$this->getNama()}: $keliling<br>";
    }
}


class Lingkaran extends Bidang
{
    public function __construct(
        string $nama,
        float $jari_jari
    ) {
        parent::__construct($nama, $jari_jari, 0);
    }

    public function luas(): void
    {
        $luas = pi() * pow($this->getSisiSatu(), 2);

        echo "Luas lingkaran {$this->getNama()}: "
            . round($luas, 2)
            . "<br>";
    }

    public function keliling(): void
    {
        $keliling = 2 * pi() * $this->getSisiSatu();

        echo "Keliling lingkaran {$this->getNama()}: "
            . round($keliling, 2)
            . "<br>";
    }
}
