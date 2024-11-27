<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\OrderModel;
use App\Models\ReturModel;
use App\Models\OrderDetailModel;

class ReturBarang extends BaseController
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
        if (!$this->session->get('username') || $this->session->get('idrole') != 1) {
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

        return view('admin/retur', $data);
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
    public function create()
    {
        $data = $this->request->getPost();
    
       

        if (empty($data['selectedItems']) || empty($data['returnQty'])) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu barang untuk diretur.');
        }
        $idorder = $data['idorder'] ?? null; 
        $alasan_pengembalian = $data['alasan_pengembalian'];
         
        if (!$idorder) {
            return redirect()->back()->with('error', 'Pilih detail order yang valid.');
        }
    

        $returnData = [];
        foreach ($data['selectedItems'] as $iddetailorder) {
            if (isset($data['returnQty'][$iddetailorder])) {
                $returnData[] = [
                    'idorder' => $idorder, 
                    'iddetailorder' => $iddetailorder,
                    'qty'    => $data['returnQty'][$iddetailorder],
                    'tanggal_pengembalian'    => date('Y-m-d H:i:s'),
                    'created_at'    => date('Y-m-d H:i:s'),
                    'status' => 'Sementara',
                    'alasan_pengembalian' => $alasan_pengembalian
                ];
            }
        }
        // dd($returnData); 
    

        if (!empty($returnData)) {
            $this->Mretur->insertBatch($returnData); 
            return redirect()->back()->with('success', 'Data retur berhasil diproses.');
        }
    
        return redirect()->back()->with('error', 'Tidak ada data retur yang diproses.');
    }
    


}
