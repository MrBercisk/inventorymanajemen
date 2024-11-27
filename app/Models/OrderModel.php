<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'trx_order'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['idsupplier','catatan','status','created_at', 'updated_at','pemesan','order_code']; 
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';  
    protected $updatedField  = 'updated_at';
    // protected $column_order = [null, 'login', 'username' ,null];
	protected $column_search = ['status'];
	protected $order = ['trx_order.id' => 'asc'];
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

    public function get_data($search = null, $order = null)
    {
        $query = $this->db->table($this->table);
        
        $query->join('sys_user', 'sys_user.id = trx_order.idsupplier', 'left'); 
        
        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

        $query->select('trx_order.status,trx_order.pemesan, trx_order.order_code, trx_order.catatan, trx_order.created_at, trx_order.updated_at, sys_user.username as nama_supplier, sys_user.alamat, sys_user.notelp'); 

        return $query->get()->getResultArray();
    }
    public function get_data_supplier($search = null, $order = null)
    {
        $query = $this->db->table($this->table);

        $query->join('sys_user', 'sys_user.id = trx_order.idsupplier', 'left');

        $idsupplier = session()->get('id');
        if ($idsupplier) {
            $query->where('trx_order.idsupplier', $idsupplier);
        }

        $this->_apply_filter($query, $search);

        if ($order && isset($this->column_order[$order['column']])) {
            $query->orderBy($this->column_order[$order['column']], $order['dir']);
        } else {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }

      
        $query->select('trx_order.id as idorder, trx_order.order_code,trx_order.catatan, trx_order.order_code, trx_order.status, trx_order.pemesan, trx_order.created_at, trx_order.updated_at, 
                        sys_user.username as nama_supplier, sys_user.alamat, sys_user.notelp');

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
    public function getorderterakhirByDate($date)
    {
        return $this->db->table('trx_order')
                        ->select('order_code')
                        ->where('DATE(created_at)', $date)
                        ->orderBy('created_at', 'DESC')
                        ->limit(1)
                        ->get()
                        ->getRowArray();
    }
    public function getPengirimanByUser($username)
    {
        $builder = $this->db->table('trx_order');
        $builder->select('trx_order.*, mst_produk.nama_produk, trx_detailorder.idorder, trx_detailorder.idproduk, trx_detailorder.qty, sys_user.alamat, trx_order.created_at as tglorder, sys_user.username as supplier'); 
        $builder->join('trx_detailorder', 'trx_detailorder.idorder = trx_order.id');  
        $builder->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk');  
        $builder->join('sys_user', 'sys_user.id = trx_order.idsupplier');  
        $builder->where('trx_order.pemesan', $username);  
        // $builder->where('trx_order.status', 'Confirmed');
    
    
        $builder->groupBy('trx_order.id');
        $query = $builder->get(); 
    
        return $query->getResultArray();  
    }
    public function getReturByUser($username)
    {
        $builder = $this->db->table('trx_order');
        $builder->select('trx_order.*, mst_produk.nama_produk, trx_detailorder.idorder, trx_detailorder.idproduk, trx_detailorder.qty, sys_user.alamat, trx_order.created_at as tglorder, sys_user.username as supplier'); 
        $builder->join('trx_detailorder', 'trx_detailorder.idorder = trx_order.id');  
        $builder->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk');  
        $builder->join('sys_user', 'sys_user.id = trx_order.idsupplier');  
        $builder->where('trx_order.pemesan', $username);  
        $builder->where('trx_order.catatan', 'Barang Diterima');  
    
    
        $builder->groupBy('trx_order.id');
        $query = $builder->get(); 
    
        return $query->getResultArray();  
    }

}
