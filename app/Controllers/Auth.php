<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
	protected $form_validation;
    protected $Muser;
    protected $session;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Muser = new UserModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data['title'] = "Inventory Kantin | Login";

        if (session()->getFlashdata('error')) {
            $data['error'] = session()->getFlashdata('error');
        }
        if (session()->getFlashdata('success')) {
            $data['success'] = session()->getFlashdata('success');
        }
    
        return view('auth/index', $data);
    }
    
   public function prosesLogin()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $this->Muser->where('username', $username)->first();

        if ($user) {
            if (md5($password) === $user['password']) {
                session()->set([
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'idrole'    => $user['idrole'],
                    'isLoggedIn'=> true
                ]);

                session()->setFlashdata('success', 'Login berhasil!');

                if ($user['idrole'] == 1) {
                    return redirect()->to(base_url('Dashboard'));
                } elseif ($user['idrole'] == 2) {
                    return redirect()->to(base_url('Supplier'));
                }
            } else {

                session()->setFlashdata('error', 'Password salah.');
                return redirect()->to('/');
            }
        } else {
    
            session()->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/');
        }
    }
    

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
