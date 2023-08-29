<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table      = 'company';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id', 
        'name',
        'contact',
        'email',
        'address',
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
    	return $this->db->query("SELECT id,name,contact,email,address FROM company")->getResult();
    }
}