<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\StockModel;

class Stock extends BaseController
{
	protected $form_validation;
    protected $Mkategori;
    protected $Muser;
    protected $Mstock;
    protected $session;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Mkategori = new KategoriModel();
        $this->Muser = new UserModel();
        $this->Mstock = new StockModel();
        $this->session = \Config\Services::session();
    }
    

    public function index()
    {
        if (!$this->session->get('username') || $this->session->get('idrole') != 1) {
            return redirect()->to('/');
        }
        $data = [
            'title' => 'Inventory Kantin | Stock Barang',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'kategori' => $this->Mkategori->findAll(),
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
        // $tes = $this->get_datatable_data();
        // dd($tes);

        return view('admin/stock', $data);
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

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'idkategori' => $this->request->getPost('idkategori'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'keterangan' => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->Mstock->insert($data)) {
            return $this->response->setJSON(['message' => 'Data berhasil ditambahkan']);
        } else {
            return $this->response->setJSON(['message' => 'Gagal menambahkan data'], 400);
        }
    }
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
