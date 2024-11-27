<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\StockModel;

class Kategori extends BaseController
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
        if (!$this->session->has('username') || $this->session->has('idrole') != 1) {
            return redirect()->to('/');
        }
        $data = [
            'title' => 'Inventory Kantin | Kategori Barang',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
        // $tes = $this->get_datatable_data();
        // dd($tes);

        return view('admin/kategori', $data);
    }
    public function get_datatable()
    {
        $request = $this->request;
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $search = $request->getPost('search')['value'];
        // $order = $request->getPost('order')[0];
   
        $data = $this->Mkategori->get_data($start);
        foreach ($data as &$row) {
            $row['action'] = '
                <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row['id'] . '"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row['id'] . '"><i class="fas fa-trash"></i></button>
            ';
        }
    
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $this->Mkategori->count_all(),
            'recordsFiltered' => $this->Mkategori->count_filtered($search),
            'data' => $data,
        ];
        // dd($response);
    
        return $this->response->setJSON($response);
    }
    public function create()
    {

        $data = [
            'nama' => $this->request->getPost('nama'),
        ];

        if ($this->Mkategori->insert($data)) {
            return $this->response->setJSON(['message' => 'Data berhasil ditambahkan']);
        } else {
            return $this->response->setJSON(['message' => 'Gagal menambahkan data'], 400);
        }
    }
    public function edit($id)
    {
        $data = $this->Mkategori->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function update($id)
    {
        $data = [
            'nama' => $this->request->getPost('nama')
        ];

        $updateStatus = $this->Mkategori->update($id, $data);

        if ($updateStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data']);
        }
    }

    public function delete($id)
    {
        if ($this->Mkategori->delete($id)) {
            return $this->response->setJSON(['message' => 'Data berhasil dihapus']);
        } else {
            return $this->response->setJSON(['message' => 'Data Gagal dihapus'], 400);
        }
    }

   

}
