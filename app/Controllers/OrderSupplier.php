<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StockModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;


class OrderSupplier extends BaseController
{
	protected $form_validation;
    protected $Muser;
    protected $Mstock;
    protected $session;
    protected $Morder;
    protected $Morderdetail;
    public function __construct(){
		$this->form_validation =  \Config\Services::validation();
        $this->Muser = new UserModel();
        $this->Mstock = new StockModel();
        $this->Morder = new OrderModel();
        $this->Morderdetail = new OrderDetailModel();
        $this->session = \Config\Services::session();
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
            'title' => 'Inventory Kantin | Dashboard',
            'nama' => $this->session->get('nama'),
            'idrole' => $this->session->get('idrole'),
            'stokminim' =>  $this->Mstock->where('stok <=', 10)->findAll(),
            'order' => $orders
        ];
        return view('supplier/order', $data);
    }
    public function getOrders()
    {
        $request = $this->request;
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $search = $request->getPost('search')['value'];
   
        $data = $this->Morder->get_data_supplier($start);
        foreach ($data as &$row) {
            $row['action'] = '
                <button class="btn btn-sm btn-warning detail-btn-stock" data-id="' . $row['idorder'] . '"><i class="fas fa-eye"></i></button>
                <button class="btn btn-sm btn-primary edit-btn-stock" data-id="' . $row['idorder'] . '"><i class="fas fa-pen"></i></button>
                <button class="btn btn-sm btn-danger delete-btn-stock" data-id="' . $row['idorder'] . '"><i class="fas fa-trash"></i></button>
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
    public function getOrderDetails()
    {
        $idorder = $this->request->getPost('idorder');
        
        $orderDetails = $this->Morderdetail->getOrderDetailsWithProduct($idorder);

        if ($orderDetails) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $orderDetails
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    public function confirmOrder()
    {
        $idorder = $this->request->getPost('idorder');

        if ($this->Morder->update($idorder, ['status' => 'Confirmed'])) {
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false], 500);
    }

    public function rejectOrder()
    {
        $idOrder = $this->request->getPost('idorder'); 
        $catatan = $this->request->getPost('catatan');

        if (!$idOrder || !$catatan) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID order dan catatan tidak boleh kosong.'
            ], 400);
        }
    
        $data = [
            'status'  => 'Rejected',
            'catatan' => $catatan,
            'updated_at' => date('Y-m-d H:i:s') 
        ];
    
        try {
            $update = $this->Morder->update($idOrder, $data);
    
            if ($update) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Order berhasil ditolak.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal memperbarui data order.'
                ], 500); 
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    



}
