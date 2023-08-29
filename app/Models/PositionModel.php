<?php

namespace App\Models;

use CodeIgniter\Model;

class PositionModel extends Model
{
    protected $table      = 'position';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id',
        'id_division',
        'id_company', 
        'name',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by', 
        'is_deleted', 
        'deleted_at',
        'deleted_by'
        ];

    function read()
    {
        return $this->db->table($this->table)->get();
    }

    function data()
    {
    	// * = SEMUA FIELDS
    	return $this->db->query("SELECT id,id_division,id_company,name,status FROM position")->getResult();
    }
}