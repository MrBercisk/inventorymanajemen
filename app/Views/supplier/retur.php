
<?= $this->include('layouts_supplier/header') ?>
<?= $this->include('layouts_supplier/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Retur Barang</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-success mb-3" id="addStockBtn"><i class="fas fa-plus"></i> Tambah</button> -->
                            <div class="table-responsive">
                            <table id="supplierReturTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Retur</th>
                                        <th>Jumlah</th>
                                        <th>Alasan Pengembalian</th>
                                        <th>Tanggal Pengembalian</th>
                                        <th>Status</th>
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

        
    <!-- /.container-fluid -->

<?= $this->include('layouts_supplier/footer') ?>

           