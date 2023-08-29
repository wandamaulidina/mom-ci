<?php

namespace App\Models;

use CodeIgniter\Model;

class DivisionModel extends Model
{
    protected $table      = 'division';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id', 
        'id_company',
        'name',
        'email',
        'contact',
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
    	return $this->db->query("SELECT id,id_company,name,email,contact FROM division")->getResult();
    }
}