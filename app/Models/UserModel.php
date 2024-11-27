<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'sys_user'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['idrole','login','username','password','alamat','notelp','created_at', 'updated_at']; 
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';  
    protected $updatedField  = 'updated_at';
    protected $column_order = [null, 'login', 'username' ,null];
	protected $column_search = ['nama'];
	protected $order = ['id' => 'asc'];
    // protected $updatedField  = 'updated_at';

    public function count_filtered($search = null)
    {
        $query = $this->db->table($this->table);
        $this->_apply_filter($query, $search);
        return $query->countAllResults();
    }

	public function count_all(){
	    $tbl_storage = $this->db->table($this->table);
	    return $tbl_storage->countAllResults();
	}

    public function get_data($search = null, $order = null)
    {
        $query = $this->db->table($this->table);
        
        $query->join('mst_role', 'mst_role.id = sys_user.idrole', 'left'); 
        
        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        $query->select('sys_user.id, sys_user.login, sys_user.username, sys_user.created_at, mst_role.role'); 

        return $query->get()->getResultArray();
    }
    private function _apply_filter(&$query, $search = null)
    {
        if (!empty($search)) {
            $first = true;
            foreach ($this->column_search as $column) {
                if ($first) {
                    $query->groupStart(); 
                    $query->like($column, $search);
                    $first = false;
                } else {
                    $query->orLike($column, $search);
                }
            }
            $query->groupEnd(); 
        }
    }
    /**
     * Fungsi untuk mendapatkan user berdasarkan username
     * @param string $username
     * @return array|null
     */
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Fungsi untuk menambahkan user baru
     * @param array $data
     * @return bool
     */
    public function addUser(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Fungsi untuk memperbarui data user berdasarkan id
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser(int $id, array $data)
    {
        return $this->update($id, $data);
    }
}
