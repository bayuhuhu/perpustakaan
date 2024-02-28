<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['member_id', 'created_at', 'updated_at', 'deleted_at']; 

    protected $useTimestamps = true; 

    protected $useSoftDeletes = true; 

    protected $validationRules = [
        'member_id' => 'required|integer',
    ];

    protected $validationMessages = [
        'member_id' => [
            'required' => 'ID Member harus diisi.',
            'integer' => 'ID Member harus berupa angka.'
        ],
    ];

}
