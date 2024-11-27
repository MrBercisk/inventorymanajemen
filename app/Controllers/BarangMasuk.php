<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\DetailProdukModel;

class BarangMasuk extends BaseController
{
	protected $form_validation;
    protected $Mkategori;
    protected $Muser;
    protected $Mstock;
    protected $Mdetail;
    protected $session;
    protected $db;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Mkategori = new KategoriModel();
        $this->Muser = new UserModel();
        $this->Mstock = new StockModel();
        $this->Mdetail = new DetailProdukModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    

    public function index()
    {
        if (!$this->session->get('username') || $this->session->get('idrole') != 1) {
            return redirect()->to('/');
        }
        $produk = $this->Mstock->findAll();
    
        $supplier = $this->Muser->table('sys_user')
            ->select('id, username')
            ->where('idrole', 2)
            ->get()
            ->getResultArray();
    
        $data = [
            'title' => 'Inventory Kantin | Barang Masuk',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'produk' => $produk,
            'supplier' => $supplier,
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
    
        return view('admin/barangmasuk', $data);
    }
    
    public function get_datatable()
    {
        $request = $this->request;
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $search = $request->getPost('search')['value'];
        // $order = $request->getPost('order')[0];
   
        $data = $this->Mdetail->get_data_barang_masuk($start);
        // dd($data);

        foreach ($data as &$row) {
            $row['action'] = '
                <button class="btn btn-sm btn-primary edit-btn-masuk" data-id="' . $row['iddetail'] . '"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-danger delete-btn-masuk" data-id="' . $row['iddetail'] . '"><i class="fas fa-trash"></i> </button>
            ';
        }
    
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $this->Mdetail->count_all(),
            'recordsFiltered' => $this->Mdetail->count_filtered($search),
            'data' => $data,
        ];
        // dd($response);
    
        return $this->response->setJSON($response);
    }
    public function create()
    {
        $this->db->transStart();

        $dataDetail = [
            'idprodukdetail' => $this->request->getPost('idprodukdetail'),
            'idsupplier' => $this->request->getPost('idsupplier'),
            'qty' => $this->request->getPost('qty'),
            'jumlah' => $this->request->getPost('jumlah'),
            'total' => $this->request->getPost('total'),
            'keterangan' => 'Barang Masuk',
            'catatan' => $this->request->getPost('catatan'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->Mdetail->insert($dataDetail)) {
            $idProdukDetail = $dataDetail['idprodukdetail'];
            $qty = (int)$dataDetail['qty'];

            $currentStock = $this->Mstock
                ->select('stok')
                ->where('id', $idProdukDetail)
                ->first();

            if ($currentStock) {
                $newStock = (int)$currentStock['stok'] + $qty;

                $this->Mstock
                    ->where('id', $idProdukDetail)
                    ->set('stok', $newStock)
                    ->update();
            } else {
                $this->db->transRollback();
                return $this->response->setJSON(['message' => 'Produk tidak ditemukan'], 400);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(['message' => 'Gagal memperbarui stok'], 400);
            }

            return $this->response->setJSON(['message' => 'Data berhasil ditambahkan dan stok diperbarui']);
        } else {
            $this->db->transRollback();
            return $this->response->setJSON(['message' => 'Gagal menambahkan data'], 400);
        }
    }



    public function edit($id)
    {
        $data = $this->Mdetail->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function update($idproduk)
    {
        $produkDetail = $this->Mdetail
            ->select('idprodukdetail, qty')
            ->where('id', $idproduk)
            ->first();
    
        if (!$produkDetail) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data produk tidak ditemukan'], 404);
        }
    
        $newQty = (int)$this->request->getPost('qty');
        $oldQty = (int)$produkDetail['qty']; 
        $selisihnya = $newQty - $oldQty;
    
        // data detail produk
        $data = [
            'idprodukdetail' => $this->request->getPost('idprodukdetail'),
            'idsupplier' => $this->request->getPost('idsupplier'),
            'qty' => $this->request->getPost('qty'),
            'jumlah' => $this->request->getPost('jumlah'),
            'total' => $this->request->getPost('total'),
            'keterangan' => 'Barang Masuk',
            'catatan' => $this->request->getPost('catatan'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    
        $this->db->transStart();
    
        // update detail
        $updateStatus = $this->Mdetail->update($idproduk, $data);
    
        if ($updateStatus) {
            $idProdukDetail = $produkDetail['idprodukdetail'];
    
            // Update stok
            $this->Mstock
                ->where('id', $idProdukDetail)
                ->set('stok', 'stok + ' . $selisihnya, false)
                ->update();

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data dan stok'], 400);
            }
    
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui dan stok diperbarui']);
        } else {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data'], 400);
        }
    }
    

    public function delete($id)
    {
        $this->db->transStart();
        $produkDetail = $this->Mdetail
            ->select('idprodukdetail, qty')
            ->where('id', $id)
            ->first();
    
        if (!$produkDetail) {
            $this->db->transRollback();
            return $this->response->setJSON(['message' => 'Data tidak ditemukan'], 404);
        }
    
        if ($this->Mdetail->delete($id)) {
            $idProdukDetail = $produkDetail['idprodukdetail'];
            $qty = (int)$produkDetail['qty'];

            $this->Mstock
                ->where('id', $idProdukDetail)
                ->set('stok', 'stok - ' . $qty, false)
                ->update();

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(['message' => 'Gagal memperbarui stok'], 400);
            }

            return $this->response->setJSON(['message' => 'Data berhasil dihapus dan stok diperbarui']);
        } else {
            $this->db->transRollback();
            return $this->response->setJSON(['message' => 'Data gagal dihapus'], 400);
        }
    }
    

   

}
