
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

 <div class="container-fluid py-5">
    <!-- Page Heading -->
    <h2 class="text-center mb-5 fw-bold text-primary">Menu Pemesanan Barang</h2>

    <div class="row">
        <!-- Table Pemesanan -->
        <div class="col-lg-6">
            <div class="bg-white shadow-lg p-4 rounded-4">
                <h5 class="fw-bold text-secondary mb-4">Daftar Pemesanan</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Harga Beli</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="orderTable">
                        <tr>
                            <td colspan="6" class="text-center text-muted">List Barang Order</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Pemesanan -->
        <div class="col-lg-6">
    <form method="post" action="<?= base_url('Order/create') ?>" class="bg-white shadow-lg p-4 rounded-4" id="addOrderForm">
        <?= csrf_field() ?>

        <!-- Supplier Selection -->
        <div class="mb-4">
            <label for="supplier_id" class="form-label fw-bold text-secondary">Pilih Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-select border-primary" required>
                <option value="" disabled selected>-- Pilih Supplier --</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= $supplier['id'] ?>"><?= $supplier['username'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Product List -->
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Pilih Barang</label>
            <div id="product-list" class="row g-3">
                <?php foreach ($produks as $product): ?>
                    <div class="col-md-6 col-lg-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-dark" style="font-size: 0.9rem;"><?= $product['nama_produk'] ?></h6>
                                <p class="card-text text-muted small mb-1"><?= $product['nama_kategori'] ?></p>
                                <p class="card-text text-muted small">Harga Beli: Rp. <span class="text-primary harga-beli"><?= $product['harga_beli'] ?></span></p>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input product-checkbox" id="product-<?= $product['idproduk'] ?>" 
                                        data-nama="<?= $product['nama_produk'] ?>" 
                                        data-harga="<?= $product['harga_beli'] ?>" 
                                        name="items[<?= $product['idproduk'] ?>][idproduk]" 
                                        value="<?= $product['idproduk'] ?>">
                                    <label class="form-check-label small" for="product-<?= $product['idproduk'] ?>">Pilih Produk</label>
                                </div>
                                <input type="hidden" name="items[<?= $product['idproduk'] ?>][harga_beli]" value="<?= $product['harga_beli'] ?>">
                                <input type="number" name="items[<?= $product['idproduk'] ?>][qty]" class="form-control product-qty" placeholder="Jumlah" min="1" disabled>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2 shadow-sm"><i class="fas fa-shopping-cart"></i> Kirim Pesanan</button>
        </div>
    </form>
</div>
    </div>
</div>

<script>
   
    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const qtyInput = this.closest('.card-body').querySelector('.product-qty');
            const productName = this.dataset.nama;
            const hargaBeli = parseFloat(this.dataset.harga);

            if (this.checked) {
                qtyInput.disabled = false;
                qtyInput.focus();
                qtyInput.addEventListener('input', function () {
                    updateOrderTable(this, productName, hargaBeli);
                });
            } else {
                qtyInput.disabled = true;
                qtyInput.value = '';
                removeOrderFromTable(productName);
            }
        });
    });

    function updateOrderTable(input, productName, hargaBeli) {
        const qty = parseFloat(input.value);
        const subtotal = qty * hargaBeli;
        const orderTable = document.getElementById('orderTable');
        let rowExists = false;

        Array.from(orderTable.rows).forEach(row => {
            if (row.dataset.name === productName) {
                rowExists = true;
                if (qty === 0 || isNaN(qty)) {
                    row.remove();
                } else {
                    row.querySelector('.qty-cell').textContent = qty;
                    row.querySelector('.subtotal-cell').textContent = `Rp. ${subtotal.toLocaleString()}`;
                }
            }
        });

        if (!rowExists && qty > 0) {
            const newRow = document.createElement('tr');
            newRow.dataset.name = productName;
            newRow.innerHTML = `
                <td>${orderTable.rows.length}</td>
                <td>${productName}</td>
                <td>Rp. ${hargaBeli.toLocaleString()}</td>
                <td class="qty-cell">${qty}</td>
                <td class="subtotal-cell">Rp. ${subtotal.toLocaleString()}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-btn">Hapus</button>
                </td>
            `;
            orderTable.appendChild(newRow);

            newRow.querySelector('.remove-btn').addEventListener('click', () => {
                input.closest('.card-body').querySelector('.product-checkbox').checked = false;
                input.disabled = true;
                input.value = '';
                newRow.remove();
            });
        }
    }

    function removeOrderFromTable(productName) {
        const orderTable = document.getElementById('orderTable');
        Array.from(orderTable.rows).forEach(row => {
            if (row.dataset.name === productName) {
                row.remove();
            }
        });
    }
    

    
</script>

        
    <!-- /.container-fluid -->

<?= $this->include('layouts_admin/footer') ?>

           