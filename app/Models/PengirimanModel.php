<?php

namespace App\Models;

use CodeIgniter\Model;

class PengirimanModel extends Model
{
    protected $table = 'trx_pengiriman'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['iddetailorder','pengiriman_code','tanggal_pengiriman','alamat_pengiriman','status_pengiriman','created_at','updated_at']; 
    protected $useTimestamps = true; 
    // protected $createdField  = 'created_at';  
    // protected $updatedField  = 'updated_at';
    // protected $column_order = [null, 'login', 'username' ,null];
	// protected $column_search = ['status'];
	protected $order = ['trx_pengiriman.id' => 'asc'];
    protected $returnType = 'array';

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
    // In Morderdetail Model (OrderDetailModel.php)
    public function getPengirimanBySupplier($idsupplier)
    {
        $builder = $this->db->table('trx_order');
        $builder->select('trx_order.*, mst_produk.nama_produk, trx_detailorder.idorder, trx_detailorder.idproduk, trx_detailorder.qty, sys_user.alamat, trx_order.created_at as tglorder'); 
        $builder->join('trx_detailorder', 'trx_detailorder.idorder = trx_order.id');  
        $builder->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk');  
        $builder->join('sys_user', 'sys_user.id = trx_order.idsupplier');  
        $builder->where('trx_order.idsupplier', $idsupplier);  
        $builder->where('trx_order.status', 'Confirmed');
        
    
        // $builder->join('trx_pengiriman', 'trx_pengiriman.iddetailorder = trx_detailorder.id', 'left');
        // $builder->where('(trx_pengiriman.status_pengiriman != "Delivered")'); 
    
        $builder->groupBy('trx_order.id');
        $query = $builder->get(); 
    
        return $query->getResultArray();  
    }
    


    // public function getPengirimanBySupplier($idsupplier)
    // {
    //     $builder = $this->db->table($this->table);
    //     // $builder->select('trx_pengiriman.*, ');
    //     $builder->select('trx_order.*, trx_detailorder.idorder, trx_detailorder.idproduk, trx_detailorder.qty');
    //     $builder->join('trx_detailorder', 'trx_detailorder.id = trx_order.id');
    //     $builder->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk');
    //     $builder->where('trx_order.status', 'Confirmed');
    //     $builder->where('trx_order.idsupplier', $idsupplier);
    //     $query = $builder->get();

    //     return $query->getResultArray();
    // }
    // public function getPengirimanBySupplier($idsupplier)
    // {
    //     $builder = $this->db->table($this->table);
    //     $builder->select('trx_pengiriman.*, trx_detailorder.idorder, trx_order.status, mst_produk.nama_produk');
    //     $builder->join('trx_detailorder', 'trx_detailorder.id = trx_pengiriman.iddetailorder');
    //     $builder->join('trx_order', 'trx_order.id = trx_detailorder.idorder');
    //     $builder->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk');
    //     $builder->where('trx_order.status', 'Confirmed');
    //     $builder->where('trx_order.idsupplier', $idsupplier);
    //     $query = $builder->get();

    //     return $query->getResultArray();
    // }

    public function get_data($search = null, $order = null)
    {
        $query = $this->db->table($this->table);
        
        $query->join('trx_order', 'trx_order.id = trx_detailorder.idorder', 'left'); 
        $query->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk', 'left'); 
        
        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        $query->select('
        mst_produk.nama_produk,
        mst_produk.harga_beli,
        trx_order.created_at,
        trx_detailorder.id,
        trx_detailorder.idorder, 
        trx_detailorder.idproduk, 
        trx_detailorder.qty, 
        trx_detailorder.harga, 
        trx_detailorder.total,
        trx_detailorder.created_at,
        trx_detailorder.updated_at,
        '); 

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
    public function getpengirimanterakhirByDate($date)
    {
        return $this->db->table('trx_pengiriman')
                        ->select('pengiriman_code')
                        ->where('DATE(created_at)', $date)
                        ->orderBy('created_at', 'DESC')
                        ->limit(1)
                        ->get()
                        ->getRowArray();
    }
}
