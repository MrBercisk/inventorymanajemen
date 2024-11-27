<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StockModel;

class Dashboard extends BaseController
{
	protected $form_validation;
    protected $Muser;
    protected $Mstock;
    protected $session;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Muser = new UserModel();
        $this->Mstock = new StockModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if (!$this->session->has('nama') && $this->session->has('idrole') != 1) {
            return redirect()->to('/');
        }
        $data = [
            'title' => 'Inventory Kantin | Dashboard',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll()
        ];
        return view('admin/index', $data);
    }
    

}
