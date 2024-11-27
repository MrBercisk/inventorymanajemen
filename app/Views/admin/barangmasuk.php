
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Barang Masuk</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" id="addMasukBtn"><i class="fas fa-plus"></i> Tambah</button>
                            <div class="table-responsive">
                                <table id="detailTable" class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Supplier</th>
                                            <th>Jumlah</th>
                                            <th>Harga/pcs</th>
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
        <div class="modal fade" id="addMasukModal" tabindex="-1" aria-labelledby="addMasukModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="addMasukForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addMasukModalLabel">Tambah Barang Masuk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                              
                                <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Nama Produk</label>
                                    <select class="form-control" id="idprodukdetail" name="idprodukdetail" required>
                                        <option value="" disabled selected>Pilih Produk</option>
                                        <?php foreach ($produk as $produknya): ?>
                                            <option value="<?= $produknya['id']; ?>"><?= $produknya['nama_produk']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Supplier</label>
                                    <select class="form-control" id="idsupplier" name="idsupplier" required>
                                        <option value="" disabled selected>Pilih Supplier</option>
                                        <?php foreach ($supplier as $suppliernya): ?>
                                            <option value="<?= $suppliernya['id']; ?>"><?= $suppliernya['username']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah(Rp)/pcs</label>
                                    <input type="number" class="form-control" id="jumlahmasuk" name="jumlah" required>
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah Barang</label>
                                    <input type="number" class="form-control" id="qtymasuk" name="qty" required>
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Total</label>
                                    <input type="number" class="form-control" id="totalmasuk" name="total" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Keterangan</label>
                                    <input type="number" class="form-control" id="keteranganmasuk" name="keterangan" value="Barang Masuk" placeholder="Barang Masuk" readonly>
                                </div>
                                <div class="form-floating col-md-12">
                                    <label for="floatingTextarea2">Catatan</label>
                                    <textarea class="form-control" placeholder="Input jika ada catatan" id="catatanmasuk" name="catatan" style="height: 100px"></textarea>
                                </div>
                                                                
                           
                            </div>
  
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            document.getElementById('qtymasuk').addEventListener('input', calculateTotal);
            document.getElementById('jumlahmasuk').addEventListener('input', calculateTotal);

            function calculateTotal() {
                var qty = parseFloat(document.getElementById('qtymasuk').value) || 0;  
                var jumlah = parseFloat(document.getElementById('jumlahmasuk').value) || 0; 

                var total = qty * jumlah;

                document.getElementById('totalmasuk').value = total.toFixed(2);
            }

        </script>
        <!-- Modal untuk Edit User -->
        <div class="modal fade" id="editMasukModal" tabindex="-1" role="dialog" aria-labelledby="editMasukModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMasukModalLabel">Edit Barang Masuk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editMasukForm">
                            <input type="hidden" name="id" id="editIdMasuk">
                            <div class="row">
                            <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Nama Produk</label>
                                    <select class="form-control" id="editidprodukdetail" name="idprodukdetail" required>
                                        <option value="" disabled selected>Pilih Produk</option>
                                        <?php foreach ($produk as $produknya): ?>
                                            <option value="<?= $produknya['id']; ?>"><?= $produknya['nama_produk']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="idkategori" class="form-label">Supplier</label>
                                    <select class="form-control" id="editidsupplier" name="idsupplier" required>
                                        <option value="" disabled selected>Pilih Supplier</option>
                                        <?php foreach ($supplier as $suppliernya): ?>
                                            <option value="<?= $suppliernya['id']; ?>"><?= $suppliernya['username']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah(Rp)/pcs</label>
                                    <input type="number" class="form-control" id="editjumlahmasuk" name="jumlah" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Jumlah Barang</label>
                                    <input type="number" class="form-control" id="editqtymasuk" name="qty" required>
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Total</label>
                                    <input type="number" class="form-control" id="edittotalmasuk" name="total" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="qty" class="form-label">Keterangan</label>
                                    <input type="number" class="form-control" id="editketeranganmasuk" name="keterangan" value="Barang Masuk" placeholder="Barang Masuk" readonly>
                                </div>
                          
                                <div class="form-floating col-md-12">
                                    <label for="floatingTextarea2">Catatan</label>
                                    <textarea class="form-control" placeholder="Input jika ada catatan" id="editcatatanmasuk" name="catatan" style="height: 100px"></textarea>
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

        <script>
            document.getElementById('editqtymasuk').addEventListener('input', calculateTotal);
            document.getElementById('editjumlahmasuk').addEventListener('input', calculateTotal);

            function calculateTotal() {
                var qty = parseFloat(document.getElementById('editqtymasuk').value) || 0;  
                var jumlah = parseFloat(document.getElementById('editjumlahmasuk').value) || 0; 

                var total = qty * jumlah;

                document.getElementById('edittotalmasuk').value = total.toFixed(2);  
            }

        </script>
        
    <!-- /.container-fluid -->

<?= $this->include('layouts_admin/footer') ?>

           