
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Log Pemesanan</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-success mb-3" id="addStockBtn"><i class="fas fa-plus"></i> Tambah</button> -->
                            <div class="table-responsive">
                                <table id="logorderTable" class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Order Kode</th>
                                            <th>Nama Produk</th>
                                            <th>Catatan</th>
                                            <th>Ongkos Kirim</th>
                                            <th>Berat(gram)</th>
                                            <th>Expedisi</th>
                                            <th>Status Order</th>
                                            <th>Tanggal Order</th>
                                            <th>Tanggal Respon Supplier</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    

                </div>
               
            </div>
                    

        </div> 
       


        
    <!-- /.container-fluid -->

<?= $this->include('layouts_admin/footer') ?>

           