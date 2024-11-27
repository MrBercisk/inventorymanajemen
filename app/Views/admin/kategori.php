
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Kategori Barang</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" id="addKategoriBtn"><i class="fas fa-plus"></i> Tambah</button>
                            <div class="table-responsive">
                                <table id="kategoriTable" class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
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
        <div class="modal fade" id="addKategoriModal" tabindex="-1" aria-labelledby="addKategoriModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="addKategoriForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addKategoriModalLabel">Tambah Kategori</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal untuk Edit Kategori -->
        <div class="modal fade" id="editKategoriModal" tabindex="-1" role="dialog" aria-labelledby="editKategoriModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKategoriModalLabel">Edit Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editKategoriForm">
                            <input type="hidden" name="id" id="editId">
                            <div class="form-group">
                                <label for="editNama">Nama Kategori</label>
                                <input type="text" class="form-control" id="editNama" name="nama" required>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        
    <!-- /.container-fluid -->

<?= $this->include('layouts_admin/footer') ?>

           