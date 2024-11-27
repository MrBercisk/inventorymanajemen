<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\OrderModel;
use App\Models\ReturModel;
use App\Models\OrderDetailModel;

class ReturSupplier extends BaseController
{
	protected $form_validation;
    protected $Mkategori;
    protected $Muser;
    protected $Mstock;
    protected $Morder;
    protected $Mretur;
    protected $Morderdetail;
    protected $session;
    protected $db;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Mkategori = new KategoriModel();
        $this->Muser = new UserModel();
        $this->Mstock = new StockModel();
        $this->Morder = new OrderModel();
        $this->Mretur = new ReturModel();
        $this->Morderdetail = new OrderDetailModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    

    public function index()
    {
        if (!$this->session->get('username') || $this->session->get('idrole') != 2) {
            return redirect()->to('/');
        }
        $username = session()->get('username');
        $supplier = $this->Muser->table('sys_user')
        ->select('id, username')
        ->where('idrole', 2)
        ->get()
        ->getResultArray();
        $produks = $this->Mstock->table('mst_produk')
        ->select('mst_produk.id as idproduk, harga_beli, mst_kategori.nama as nama_kategori, nama_produk')
        ->join('mst_kategori','mst_kategori.id = mst_produk.idkategori')
        ->get()
        ->getResultArray();
        $data = [
            'title' => 'Inventory Kantin | Retur Barang',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'suppliers' => $supplier,
            'produks' => $produks,
            'datanya' => $this->Morder->getReturByUser($username),
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
        // $tes = $this->get_datatable_data();
        // dd($data['produks']);

        return view('supplier/retur', $data);
    }
    public function getOrders()
    {
        $idorder = $this->request->getPost('idorder');
        $request = $this->request;
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $search = $request->getPost('search')['value'];
   
        $data = $this->Mretur->getReturnsBySupplier($start);
        foreach ($data as &$row) {
            $row['action'] = '
                <button class="btn btn-sm btn-warning detail-btn-stock" data-id="' . $row['id'] . '"><i class="fas fa-eye"></i></button>
                <button class="btn btn-sm btn-primary edit-btn-stock" data-id="' . $row['id'] . '"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-danger delete-btn-stock" data-id="' . $row['id'] . '"><i class="fas fa-trash"></i></button>
            ';
        }
    
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $this->Mretur->count_all(),
            'recordsFiltered' => $this->Mretur->count_filtered($search),
            'data' => $data,
        ];
        // dd($response);
    
        return $this->response->setJSON($response);
    }
    public function getOrderDetails($idorder)
    {
        $data = $this->Morderdetail
                    ->join('trx_order', 'trx_order.id = trx_detailorder.idorder')
                    ->join('mst_produk', 'mst_produk.id = trx_detailorder.idproduk')
                    ->select('trx_detailorder.id as iddetailorder, trx_detailorder.qty, trx_order.order_code, mst_produk.nama_produk')
                    ->where('idorder', $idorder)
                    ->findAll();

        return $this->response->setJSON($data);
    }
    public function confirmRetur()
    {
        $idretur = $this->request->getPost('id');

        if ($this->Mretur->update($idretur, ['status' => 'Sukses'])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false], 500);
    }



}
