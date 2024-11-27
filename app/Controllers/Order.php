<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;

class Order extends BaseController
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
            'title' => 'Inventory Kantin | Stock Barang',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'suppliers' => $supplier,
            'produks' => $produks,
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
        // $tes = $this->get_datatable_data();
        // dd($data['produks']);

        return view('admin/order', $data);
    }
    public function get_datatable()
    {
        $request = $this->request;
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $search = $request->getPost('search')['value'];
        // $order = $request->getPost('order')[0];
   
        $data = $this->Mstock->get_data($start);
        foreach ($data as &$row) {
            $row['action'] = '
                <button class="btn btn-sm btn-primary edit-btn-stock" data-id="' . $row['idproduk'] . '"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-danger delete-btn-stock" data-id="' . $row['idproduk'] . '"><i class="fas fa-trash"></i></button>
            ';
        }
    
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $this->Mstock->count_all(),
            'recordsFiltered' => $this->Mstock->count_filtered($search),
            'data' => $data,
        ];
        // dd($response);
    
        return $this->response->setJSON($response);
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
                // $insert = $this->Morderdetail->insert($trxOrderDetailData);
                if ($this->Morderdetail->insert($trxOrderDetailData)) {
                    return $this->response->setJSON(['message' => 'Data berhasil ditambahkan']);
                } else {
                    return $this->response->setJSON(['message' => 'Gagal menambahkan data'], 400);
                }
            }
        }
    
        // return redirect()->to('/order')->with('message', 'Pesanan berhasil dibuat.');
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
