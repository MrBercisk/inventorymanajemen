<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\StockModel;

class UserManagement extends BaseController
{
	protected $form_validation;
    protected $Muser;
    protected $Mrole;
    protected $Mstock;
    protected $session;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Muser = new UserModel();
        $this->Mrole = new RoleModel();
        $this->Mstock = new StockModel();
        $this->session = \Config\Services::session();
    }
    

    public function index()
    {
        if (!$this->session->has('nama') && $this->session->has('idrole') != 1) {
            return redirect()->to('/');
        }
        $data = [
            'title' => 'Inventory Kantin | User Management',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'roles' => $this->Mrole->findAll(),
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
        // $tes = $this->get_datatable_data();
        // dd($tes);

        return view('admin/users', $data);
    }
    public function get_datatable()
    {
        $request = $this->request;
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $search = $request->getPost('search')['value'];
        // $order = $request->getPost('order')[0];
   
        $data = $this->Muser->get_data($start);
        foreach ($data as &$row) {
            $row['action'] = '
                <button class="btn btn-sm btn-primary edit-btn-users" data-id="' . $row['id'] . '"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-danger delete-btn-users" data-id="' . $row['id'] . '"><i class="fas fa-trash"></i></button>
            ';
        }
    
        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $this->Muser->count_all(),
            'recordsFiltered' => $this->Muser->count_filtered($search),
            'data' => $data,
        ];
        // dd($response);
    
        return $this->response->setJSON($response);
    }
    public function create()
    {

        $data = [
            'username' => $this->request->getPost('username'),
            'login' => $this->request->getPost('login'),
            'password' => md5($this->request->getPost('password')),
            'idrole' => $this->request->getPost('idrole'),
            'created_at' =>  date('Y-m-d H:i:s')
        ];

        if ($this->Muser->insert($data)) {
            return $this->response->setJSON(['message' => 'Data berhasil ditambahkan']);
        } else {
            return $this->response->setJSON(['message' => 'Gagal menambahkan data'], 400);
        }
    }
    public function edit($id)
    {
        $data = $this->Muser->find($id);
        
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function update($id)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'login' => $this->request->getPost('login'),
            'password' => md5($this->request->getPost('password')),
            'idrole' => $this->request->getPost('idrole'),
            'updated_at' =>  date('Y-m-d H:i:s')
        ];

        $updateStatus = $this->Muser->update($id, $data);

        if ($updateStatus) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data']);
        }
    }

    public function delete($id)
    {
        if ($this->Muser->delete($id)) {
            return $this->response->setJSON(['message' => 'Data berhasil dihapus']);
        } else {
            return $this->response->setJSON(['message' => 'Data Gagal dihapus'], 400);
        }
    }

   

}
