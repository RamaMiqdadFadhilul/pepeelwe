<?php

class User
{
    private int $iduser;
    private string $nama;
    private string $email;
    private array $role = [];

    public function __construct(
        int $iduser,
        string $nama,
        string $email
    ) {
        $this->iduser = $iduser;
        $this->nama = $nama;
        $this->email = strtolower(trim($email));
    }

    public function get_user(): array
    {
        $role_aktif = $this->get_role_aktif();

        return [
            'iduser' => $this->iduser,
            'nama' => $this->nama,
            'email' => $this->email,
            'role' => $role_aktif === null
                ? '-'
                : $role_aktif->get_data()['nama_role']
        ];
    }

    public function set_role(Role $role): void
    {
        if ($role->get_status() === true) {

            foreach ($this->role as $r) {
                $r->set_status(false);
            }
        }

        $this->role[] = $role;
    }

    public function get_role_aktif(): ?Role
    {
        foreach ($this->role as $r) {

            if ($r->get_status() === true) {
                return $r;
            }
        }

        return null;
    }

    public function hapus_role(int $idrole): void
    {
        foreach ($this->role as $index => $role) {

            if ($role->get_data()['idrole'] === $idrole) {
                unset($this->role[$index]);
            }
        }

        $this->role = array_values($this->role);
    }

    public function set_role_aktif(int $idrole): void
    {
        $ditemukan = false;

        foreach ($this->role as $role) {

            $data = $role->get_data();

            if ($data['idrole'] === $idrole) {

                $role->set_status(true);
                $ditemukan = true;

            } else {

                $role->set_status(false);
            }
        }

        if (!$ditemukan) {

            throw new InvalidArgumentException(
                "Role dengan ID tersebut tidak ditemukan."
            );
        }
    }

    public function __toString(): string
    {
        return $this->nama . " <" . $this->email . ">";
    }
}