<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'mst_produk'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['mst_kategori.nama as nama_kategori','idkategori','nama_produk','harga_beli','harga_jual','stok','keterangan','created_at', 'updated_at']; 
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';  
    protected $updatedField  = 'updated_at';
    // protected $column_order = [null, 'login', 'username' ,null];
	protected $column_search = ['nama_produk'];
	protected $order = ['mst_produk.id' => 'asc'];

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
        
        $query->join('mst_kategori', 'mst_kategori.id = mst_produk.idkategori', 'left'); 
        
        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        $query->select('mst_produk.id as idproduk, mst_produk.nama_produk, mst_produk.harga_beli, mst_produk.harga_jual, mst_produk.stok, mst_produk.keterangan, mst_produk.created_at, mst_produk.updated_at, mst_kategori.nama as nama_kategori'); 

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
