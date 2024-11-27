<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table = 'mst_kategori'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama','created_at','updated_at']; 
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $column_order = [null, 'nama', null];
	protected $column_search = ['nama'];
	protected $order = ['id' => 'asc'];

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

        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

    
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


   
}
