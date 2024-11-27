<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table = 'trx_detailorder'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_produk','idorder','idproduk','qty','harga','total','created_at','updated_at']; 
    protected $useTimestamps = true; 
    // protected $createdField  = 'created_at';  
    // protected $updatedField  = 'updated_at';
    // protected $column_order = [null, 'login', 'username' ,null];
	// protected $column_search = ['status'];
	protected $order = ['trx_detailorder.id' => 'asc'];
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

        public function getOrderDetailsWithProduct($idorder)
        {
            $builder = $this->db->table('trx_detailorder');
            $builder->select('trx_detailorder.*, mst_produk.nama_produk, trx_order.order_code'); 
            $builder->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk');  
            $builder->join('trx_order', 'trx_order.id = trx_detailorder.idorder');  
            $builder->where('trx_detailorder.idorder', $idorder);  
            $query = $builder->get(); 

            return $query->getResultArray();  
        }


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
}
