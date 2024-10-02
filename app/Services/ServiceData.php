<?php

namespace App\Services;

class ServiceData
{
    public const biodata = [
        'name' => 'Ihsan',
        'email' => 'ihsan@gmail.com',
    ];

    public function namaSaya($nama, $asal)
    {
        return "Nama saya adalah $nama, saya berasal dari $asal";
    }

    public function namaPelatihan($nama, $lembaga, $tahun)
    {
        $nama = env('PELATIHAN', $nama);
        return "Nama pelatihan adalah $nama, lembaga penyelenggara adalah $lembaga pada tahun $tahun";
    }
}
