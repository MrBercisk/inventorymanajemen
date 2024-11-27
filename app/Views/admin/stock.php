
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Stock Barang</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" id="addStockBtn"><i class="fas fa-plus"></i> Tambah</button>
                            <div class="table-responsive">
                                <table id="stockTable" class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kategori</th>
                                            <th>Nama Produk</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Stock</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal Input</th>
                                            <th>Tanggal Update</th>
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
        <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="addStockForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStockModalLabel">Tambah Stock</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_produk" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Kategori Produk</label>
                                    <select class="form-control" id="idkategori" name="idkategori" required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        <?php foreach ($kategori as $kategorinya): ?>
                                            <option value="<?= $kategorinya['id']; ?>"><?= $kategorinya['nama']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="harga_beli" class="form-label">Harga Beli</label>
                                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="harga_jual" class="form-label">Harga Jual</label>
                                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" required>
                                    <div id="hargaWarning" class="alert alert-danger d-none" role="alert">
                                        < Harga beli, Anda rugi!
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan">
                                </div>
                                <!-- <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" readonly>
                                </div> -->
                            </div>
  
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal untuk Edit User -->
        <div class="modal fade" id="editStockModal" tabindex="-1" role="dialog" aria-labelledby="editStockModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStockModalLabel">Edit Stock</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editStockForm">
                            <input type="hidden" name="id" id="editIdStock">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editnamaproduk">Nama Produk</label>
                                    <input type="text" class="form-control" id="editnamaproduk" name="nama_produk" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                        <label for="idkategori" class="form-label">Kategori Produk</label>
                                        <select class="form-control" id="editidkategori" name="idkategori" required>
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <?php foreach ($kategori as $kategorinya): ?>
                                                <option value="<?= $kategorinya['id']; ?>"><?= $kategorinya['nama']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="harga_beli" class="form-label">Harga Beli</label>
                                    <input type="number" class="form-control" id="editharga_beli" name="harga_beli" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="harga_jual" class="form-label">Harga Jual</label>
                                    <input type="number" class="form-control" id="editharga_jual" name="harga_jual" required>
                                    <div id="hargaWarning" class="alert alert-danger d-none" role="alert">
                                        < Harga beli, Anda rugi!
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="editketerangan" name="keterangan">
                                </div>
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

           