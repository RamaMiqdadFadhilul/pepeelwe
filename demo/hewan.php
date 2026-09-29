<?php

class Hewan
{
    private int $jumlah_kaki;
    private int $tingkat_lapar;
    protected int $posisi;

    public function __construct(
        int $jumlah_kaki,
        int $tingkat_lapar,
        int $posisi
    ) {
        $this->jumlah_kaki = $jumlah_kaki;
        $this->tingkat_lapar = $tingkat_lapar;
        $this->posisi = $posisi;
    }

    public function makan(int $kalori): bool
    {
        $this->tingkat_lapar -= $kalori;

        if ($this->tingkat_lapar < 0) {
            $this->tingkat_lapar = 0;
            return false;
        }

        return true;
    }

    public function lari(int $meter): void
    {
        $this->posisi += $meter;

        echo "Hewan berlari sejauh $meter meter.<br>";
    }

    public function bersuara(): void
    {
        echo "Hewan bersuara.<br>";
    }

    public function get_tingkat_lapar(): int
    {
        return $this->tingkat_lapar;
    }

    public function get_posisi(): int
    {
        return $this->posisi;
    }
}


class Kucing extends Hewan
{
    private string $nama;

    public function __construct(
        string $nama,
        int $tingkat_lapar,
        int $posisi
    ) {
        parent::__construct(
            4,
            $tingkat_lapar,
            $posisi
        );

        $this->nama = $nama;
    }

    public function lompat(int $jarak): void
    {
        $this->posisi += $jarak;

        echo "$this->nama melompat sejauh $jarak meter.<br>";
    }

    public function bersuara(): void
    {
        echo "$this->nama berkata: Meong Meong!<br>";
    }
}
