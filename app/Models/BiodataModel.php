<?php

namespace App\Models;

use CodeIgniter\Model;

class BiodataModel extends Model
{
    protected $table      = 'biodata';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'ttl',
        'alamat',
        'email',
        'telepon',
        'deskripsi',
        'foto'
    ];
}
