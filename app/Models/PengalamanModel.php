<?php

namespace App\Models;

use CodeIgniter\Model;

class PengalamanModel extends Model
{
    protected $table      = 'pengalaman';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'posisi',
        'instansi',
        'tahun_mulai',
        'tahun_selesai',
        'deskripsi',
        'biodata_id'
    ];

    protected $useTimestamps = false;
}
