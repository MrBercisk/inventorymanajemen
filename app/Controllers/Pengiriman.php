<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\PengirimanModel;


class Pengiriman extends BaseController
{
	protected $form_validation;
    protected $Muser;
    protected $Mstock;
    protected $session;
    protected $Morder;
    protected $Morderdetail;
    protected $Mpengiriman;
    protected $db;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Muser = new UserModel();
        $this->Mstock = new StockModel();
        $this->Morder = new OrderModel();
        $this->Morderdetail = new OrderDetailModel();
        $this->Mpengiriman = new PengirimanModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!$this->session->has('nama') && $this->session->has('idrole') != 2) {
            return redirect()->to('/');
        }
        $idsuppliernya = session()->get('id');
        if (!$idsuppliernya) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    
        $orders = $this->Morder
        ->where('idsupplier', $idsuppliernya)
        ->whereIn('status', ['Pending'])
        ->findAll();

        $data = [
            'title' => 'Inventory Kantin | Pengiriman Barang',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll(),
            'order' => $orders,
            'pengiriman' => $this->Mpengiriman->getPengirimanBySupplier($idsuppliernya)
        ];
        // dd($data['pengiriman']);
        return view('supplier/pengiriman', $data);
    }
    public function prosesPengiriman()
    {
        $tanggal = date('Y-m-d');
        $ymd = date('ymd', strtotime($tanggal));
    
        $orderterakhir = $this->Mpengiriman->getpengirimanterakhirByDate($tanggal);
        $noterakhir = $orderterakhir ? (int) substr($orderterakhir['pengiriman_code'], -3) : 0;
        $orderselanjutnya = str_pad($noterakhir + 1, 3, '0', STR_PAD_LEFT);
    
        $order_code = $ymd . $orderselanjutnya;
    
        $iddetailorder = $this->request->getPost('iddetailorder');
        $ongkir = $this->request->getPost('ongkir');
        $berat_gram = $this->request->getPost('weight');
        $expedisi = $this->request->getPost('courier');
        $tanggal_pengiriman = $this->request->getPost('tanggal_pengiriman');
        $alamat_pengiriman = $this->request->getPost('alamat_pengiriman');

        $data = [
            'iddetailorder' => $iddetailorder,
            'pengiriman_code' => $order_code,
            'tanggal_pengiriman' => $tanggal_pengiriman,
            'alamat_pengiriman' => $alamat_pengiriman,
            'status_pengiriman' => 'Delivered',
        ];
    
        $insert = $this->Mpengiriman->insert($data);
    
        if ($insert) {
            $updateData = [
                'ongkir' => $ongkir,
                'berat_gram' => $berat_gram,
                'expedisi' => $expedisi,
                'catatan' => 'Proses Pengiriman'
            ];
            $this->db->table('trx_order')
                     ->where('id', $iddetailorder)
                     ->update($updateData);
    
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pengiriman berhasil disimpan dan diperbarui'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan atau gagal disimpan'
            ]);
        }
    }
    
    
    


}
