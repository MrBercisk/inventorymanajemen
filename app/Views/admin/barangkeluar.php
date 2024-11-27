
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Barang Keluar</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" id="addKeluarBtn"><i class="fas fa-plus"></i> Tambah</button>
                            <div class="table-responsive">
                                <table id="detailKeluarTable" class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Supplier</th>
                                            <th>Jumlah</th>
                                            <th>Jumlah/pcs</th>
                                            <th>Total</th>
                                            <th>Keterangan</th>
                                            <th>Catatan</th>
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
        <div class="modal fade" id="addKeluarModal" tabindex="-1" aria-labelledby="addKeluarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="addKeluarForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addKeluarModalLabel">Tambah Barang Keluar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                              
                                <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Nama Produk</label>
                                    <select class="form-control" id="idprodukdetailkeluar" name="idprodukdetail" required>
                                        <option value="" disabled selected>Pilih Produk</option>
                                        <?php foreach ($produk as $produknya): ?>
                                            <option value="<?= $produknya['id']; ?>"><?= $produknya['nama_produk']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Supplier</label>
                                    <select class="form-control" id="idsupplierkeluar" name="idsupplier" required>
                                        <option value="" disabled selected>Pilih Supplier</option>
                                        <?php foreach ($supplier as $suppliernya): ?>
                                            <option value="<?= $suppliernya['id']; ?>"><?= $suppliernya['username']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Keterangan</label>
                                    <input type="number" class="form-control" id="keterangankeluar" name="keterangan" value="Barang Masuk" placeholder="Barang Masuk" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah Barang</label>
                                    <input type="number" class="form-control" id="qtykeluar" name="qty" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah(Rp)/pcs</label>
                                    <input type="number" class="form-control" id="jumlahkeluar" name="jumlah" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Total</label>
                                    <input type="number" class="form-control" id="totalkeluar" name="total" required>
                                </div>
                          
                                <div class="form-floating col-md-12">
                                    <label for="floatingTextarea2">Catatan</label>
                                    <textarea class="form-control" placeholder="Input jika ada catatan" id="catatankeluar" name="catatan" style="height: 100px"></textarea>
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
        <div class="modal fade" id="editKeluarModal" tabindex="-1" role="dialog" aria-labelledby="editKeluarModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKeluarModalLabel">Edit Barang Keluar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editKeluarForm">
                            <input type="hidden" name="id" id="editIdKeluar">
                            <div class="row">
                            <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Nama Produk</label>
                                    <select class="form-control" id="editidprodukkeluar" name="idprodukdetail" required>
                                        <option value="" disabled selected>Pilih Produk</option>
                                        <?php foreach ($produk as $produknya): ?>
                                            <option value="<?= $produknya['id']; ?>"><?= $produknya['nama_produk']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Supplier</label>
                                    <select class="form-control" id="editidsupplierkeluar" name="idsupplier" required>
                                        <option value="" disabled selected>Pilih Supplier</option>
                                        <?php foreach ($supplier as $suppliernya): ?>
                                            <option value="<?= $suppliernya['id']; ?>"><?= $suppliernya['username']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah(Rp)/pcs</label>
                                    <input type="number" class="form-control" id="editjumlahkeluar" name="jumlah" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah Barang</label>
                                    <input type="number" class="form-control" id="editqtykeluar" name="qty" required>
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Total</label>
                                    <input type="number" class="form-control" id="edittotalkeluar" name="total" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Keterangan</label>
                                    <input type="number" class="form-control" id="editketerangankeluar" name="keterangan" value="Barang Keluar" placeholder="Barang Keluar" readonly>
                                </div>
                          
                                <div class="form-floating col-md-12">
                                    <label for="floatingTextarea2">Catatan</label>
                                    <textarea class="form-control" placeholder="Input jika ada catatan" id="editcatatankeluar" name="catatan" style="height: 100px"></textarea>
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

           