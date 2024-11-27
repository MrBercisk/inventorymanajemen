<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;

class LogOrder extends BaseController
{
	protected $form_validation;
    protected $Mkategori;
    protected $Muser;
    protected $Mstock;
    protected $Morder;
    protected $Morderdetail;
    protected $session;
    protected $db;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Mkategori = new KategoriModel();
        $this->Muser = new UserModel();
        $this->Mstock = new StockModel();
        $this->Morder = new OrderModel();
        $this->Morderdetail = new OrderDetailModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    

    public function index()
    {
        if (!$this->session->get('username') || $this->session->get('idrole') != 1) {
            return redirect()->to('/');
        }
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
            'title' => 'Inventory Kantin | ;Log Pemesanan',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'suppliers' => $supplier,
            'produks' => $produks,
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
        // $tes = $this->get_datatable_data();
        // dd($data['produks']);

        return view('admin/logorder', $data);
    }
    public function get_datatable()
    {
        $username = session()->get('username');
    
        if (!$username) {
            return $this->response->setJSON([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    
        $request = $this->request;
        $draw = $request->getPost('draw');
        $start = $request->getPost('start') ?: 0;
        $search = $request->getPost('search')['value'] ?: '';
    
        $data = $this->Morder->getPengirimanByUser($username);
    
        if ($search) {
            $data = array_filter($data, function ($row) use ($search) {
                return stripos($row['nama_produk'], $search) !== false || 
                       stripos($row['alamat'], $search) !== false || 
                       stripos($row['tglorder'], $search) !== false;
            });
        }

       
        $recordsTotal = count($data);
        $recordsFiltered = count($data);
    
        $length = $request->getPost('length') ?: 10;
        $data = array_slice($data, $start, $length);
    
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
    
        return $this->response->setJSON($response);
    }
    public function updateCatatan()
    {
        $id = $this->request->getPost('id');
        $catatan = $this->request->getPost('catatan');

        $updateData = [
            'catatan' => $catatan,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $result = $this->db->table('trx_order')
            ->where('id', $id)
            ->update($updateData);

        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'Catatan berhasil diperbarui']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal memperbarui catatan']);
        }
    }

    public function create()
    {
        $pemesan = session()->get('username');
        $supplierId = $this->request->getPost('supplier_id');
        $items = $this->request->getPost('items');
        // dd($items);
    
        if (!$supplierId || !$items || !is_array($items)) {
            return redirect()->back()->with('error', 'Harap pilih supplier dan barang.');
        }
    
        $tanggal = date('Y-m-d');
        $ymd = date('ymd', strtotime($tanggal));
    
        $orderterakhir = $this->Morder->getorderterakhirByDate($tanggal);
        $noterakhir = $orderterakhir ? (int) substr($orderterakhir['order_code'], -3) : 0;
        $orderselanjutnya = str_pad($noterakhir + 1, 3, '0', STR_PAD_LEFT); 
    
        $order_code = $ymd . $orderselanjutnya;
        $trxOrderData = [
            'idsupplier' => $supplierId,
            'status'     => 'Pending',
            'created_at' => date('Y-m-d H:i:s'),
            'pemesan'    => $pemesan,
            'order_code' => $order_code
        ];
    
        $trxOrderId = $this->Morder->insert($trxOrderData);
    
        if (!$trxOrderId) {
            return redirect()->back()->with('error', 'Gagal menyimpan data pesanan.');
        }
    
        // Insert data ke tabel trx_order_detail
        foreach ($items as $item) {
            if (isset($item['idproduk']) && !empty($item['idproduk'])) {
        
                $qty = $item['qty'];
                $harga = $item['harga_beli'];
                $total = $qty * $harga;
               

                $trxOrderDetailData = [
                    'idorder'  => $trxOrderId,
                    'idproduk' => $item['idproduk'],
                    'qty'      => $qty,
                    'harga'    => $harga,
                    'total'    => $total
                ];
                $this->Morderdetail->insert($trxOrderDetailData);
            }
        }
    
        return redirect()->to('/order')->with('message', 'Pesanan berhasil dibuat.');
    }
    
    

    // public function create()
    // {

    //     $data = [
    //         'nama_produk' => $this->request->getPost('nama_produk'),
    //         'idkategori' => $this->request->getPost('idkategori'),
    //         'harga_beli' => $this->request->getPost('harga_beli'),
    //         'harga_jual' => $this->request->getPost('harga_jual'),
    //         'keterangan' => $this->request->getPost('keterangan'),
    //         'created_at' => date('Y-m-d H:i:s')
    //     ];

    //     if ($this->Mstock->insert($data)) {
    //         return $this->response->setJSON(['message' => 'Data berhasil ditambahkan']);
    //     } else {
    //         return $this->response->setJSON(['message' => 'Gagal menambahkan data'], 400);
    //     }
    // }
    public function edit($id)
    {
        $data = $this->Mstock->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function update($idproduk)
    {
        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'idkategori' => $this->request->getPost('idkategori'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'keterangan' => $this->request->getPost('keterangan'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updateStatus = $this->Mstock->update($idproduk, $data);

        if ($updateStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data']);
        }
    }

    public function delete($id)
    {
        if ($this->Mstock->delete($id)) {
            return $this->response->setJSON(['message' => 'Data berhasil dihapus']);
        } else {
            return $this->response->setJSON(['message' => 'Data Gagal dihapus'], 400);
        }
    }

   

}
