<?php

namespace App\Models;

use CodeIgniter\Model;

class PendidikanModel extends Model
{
    protected $table      = 'pendidikan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jenjang',
        'institusi',
        'tahun_mulai',
        'tahun_selesai',
        'keterangan',
        'biodata_id'
    ];

    protected $useTimestamps = false;
}
