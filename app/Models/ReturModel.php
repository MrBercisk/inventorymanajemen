<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturModel extends Model
{
    protected $table = 'trx_retur'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['idorder','iddetailorder','tanggal_pengembalian','alasan_pengembalian','qty','status','created_at','updated_at']; 
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

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
    public function getReturnsBySupplier($idsupplier)
    {
        $idsupplier = session()->get('id');
        return $this->select('trx_retur.*, trx_order.order_code, trx_order.created_at')
            ->join('trx_order', 'trx_order.id = trx_retur.idorder')
            ->join('sys_user', 'sys_user.id = trx_order.idsupplier')
            ->where('trx_order.idsupplier', $idsupplier)
            ->whereIn('trx_retur.status', ['Sukses', 'Sementara'])  // Fetch records where status is either 'Sukses' or 'Sementara'
            ->findAll();
    }


   
}
