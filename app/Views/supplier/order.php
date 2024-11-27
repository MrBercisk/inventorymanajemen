
<?= $this->include('layouts_supplier/header') ?>
<?= $this->include('layouts_supplier/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Order Barang</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-success mb-3" id="addStockBtn"><i class="fas fa-plus"></i> Tambah</button> -->
                            <div class="table-responsive">
                            <table id="supplierOrderTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Order</th>
                                        <th>Pemesan</th>
                                        <th>Status</th>
                                        <th>Alasan(Jika reject order)</th>
                                        <th>Tanggal Pesanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            </div>
                        </div>
                    </div>
                    

                </div>
               
            </div>
                    

        </div> 
        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <ul id="orderDetailsList">
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    </div>
                </div>
            </div>
        </div>

        
    <!-- /.container-fluid -->

<?= $this->include('layouts_supplier/footer') ?>

           