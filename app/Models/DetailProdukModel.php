<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailProdukModel extends Model
{
    protected $table = 'trx_detailbarang'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['idprodukdetail','idsupplier','qty','jumlah','total', 'catatan','keterangan','created_at', 'updated_at']; 
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';  
    protected $updatedField  = 'updated_at';
    // protected $column_order = [null, 'login', 'username' ,null];
	protected $column_search = ['keterangan'];
	protected $order = ['trx_detailbarang.id' => 'asc'];

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
        
        $query->join('mst_produk', 'mst_produk.id = trx_detailbarang.idprodukdetail', 'left'); 
        $query->join('sys_user', 'sys_user.id = trx_detailbarang.idsupplier', 'left'); 
        
        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        $query->select('trx_detailbarang.id as iddetail, trx_detailbarang.catatan, mst_produk.nama_produk, sys_user.username as nama_supplier, trx_detailbarang.qty, trx_detailbarang.jumlah, trx_detailbarang.total, trx_detailbarang.keterangan, trx_detailbarang.created_at, trx_detailbarang.updated_at '); 

        return $query->get()->getResultArray();
    }
    public function get_data_barang_masuk($search = null, $order = null)
    {
        $query = $this->db->table($this->table);

        $query->join('mst_produk', 'mst_produk.id = trx_detailbarang.idprodukdetail', 'left');
        $query->join('sys_user', 'sys_user.id = trx_detailbarang.idsupplier', 'left');
        $query->where('trx_detailbarang.keterangan', 'Barang Masuk');

        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        $query->select('trx_detailbarang.id as iddetail, trx_detailbarang.catatan, mst_produk.nama_produk, sys_user.username as nama_supplier, trx_detailbarang.qty, trx_detailbarang.jumlah, trx_detailbarang.total, trx_detailbarang.keterangan, trx_detailbarang.created_at, trx_detailbarang.updated_at');

        return $query->get()->getResultArray();
    }

    public function get_data_barang_keluar($search = null, $order = null)
    {
        $query = $this->db->table($this->table);

        $query->join('mst_produk', 'mst_produk.id = trx_detailbarang.idprodukdetail', 'left');
        $query->join('sys_user', 'sys_user.id = trx_detailbarang.idsupplier', 'left');
        $query->where('trx_detailbarang.keterangan', 'Barang Keluar');

        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        $query->select('trx_detailbarang.id as iddetail, trx_detailbarang.catatan, mst_produk.nama_produk, sys_user.username as nama_supplier, trx_detailbarang.qty, trx_detailbarang.jumlah, trx_detailbarang.total, trx_detailbarang.keterangan, trx_detailbarang.created_at, trx_detailbarang.updated_at');

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
