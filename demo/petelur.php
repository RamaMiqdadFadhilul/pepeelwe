<?php

interface Petelur
{
    public function bertelur(): void;
}


class HewanTernak
{
    protected string $nama;

    public function __construct(string $nama)
    {
        $this->nama = $nama;
    }

    public function berlari(): void
    {
        echo "{$this->nama} sedang berlari<br>";
    }
}


class KucingTernak extends HewanTernak
{
    public function __construct(string $nama)
    {
        parent::__construct($nama);
    }
}


class Ayam extends HewanTernak implements Petelur
{
    public function __construct(string $nama)
    {
        parent::__construct($nama);
    }

    public function bertelur(): void
    {
        echo "{$this->nama} sedang bertelur<br>";
    }
}
