
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

 <div class="container-fluid py-5">
    <!-- Page Heading -->
    <h2 class="text-center mb-5 fw-bold text-primary">Form Retur Barang</h2>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="<?= base_url('ReturBarang/create') ?>" method="post" id="proseskirim">
                        <div class="form-group">
                            <label for="detailOrder">Pilih Detail Order</label>
                            <select class="form-control" id="detailOrder" name="idorder" required>
                                <option value="" disabled selected>-- Pilih Detail Order --</option>
                                <?php foreach ($datanya as $item): ?>
                                    <option value="<?= $item['idorder'] ?>" data-order_code="<?= $item['order_code'] ?>" data-pemesan="<?= $item['supplier'] ?>" data-ongkir="<?= $item['ongkir'] ?>" data-expedisi="<?= $item['expedisi'] ?>" data-created_at="<?= $item['created_at'] ?>">
                                        Order ID: <?= $item['order_code'] ?> - <?= $item['supplier'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="orderDetailsContainer" style="display: none;">
                            <h5>Detail Barang</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Produk</th>
                                        <th>Maks Pengembalian</th>
                                        <th>Jumlah Pengembalian</th>
                                    </tr>
                                </thead>
                                <tbody id="orderDetailsTable">

                                </tbody>
                            </table>
                        </div>

                        <div class="form-group" id="orderDetails" style="display: none;">
                            <label for="order_code">Order Code</label>
                            <input type="text" class="form-control" id="order_code" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                            <input type="text" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" value="<?= date('Y-m-d'); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="alasan_pengembalian">Alasan Pengembalian</label>
                            <textarea class="form-control" id="alasan_pengembalian" name="alasan_pengembalian" rows="3" required></textarea>
                        </div>


                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Proses Retur</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('detailOrder').addEventListener('change', function () {
    var selectedOption = this.options[this.selectedIndex];
    var idorder = selectedOption.value;

    fetch(`<?= base_url('ReturBarang/getOrderDetails') ?>/${idorder}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('orderDetailsTable');
            tableBody.innerHTML = ''; 

            data.forEach(item => {
                const row = `<tr>
                    <td>
                        <input type="checkbox" name="selectedItems[]" value="${item.iddetailorder}" class="item-checkbox">
                    </td>
                    <td>${item.nama_produk}</td>
                    <td>${item.qty} Unit</td>
                    <td>
                        <input type="number" class="form-control qty-input" name="returnQty[${item.iddetailorder}]" min="1" max="${item.qty}" value="1">
                    </td>
                </tr>`;
                tableBody.innerHTML += row;
            });

            document.getElementById('orderDetailsContainer').style.display = 'block';

            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const qtyInput = this.closest('tr').querySelector('.qty-input');
                    qtyInput.disabled = !this.checked;
                    if (!this.checked) qtyInput.value = 1; 
                });
            });
        })
        .catch(error => console.error('Error fetching order details:', error));
});

    document.getElementById('detailOrder').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        // console.log(selectedOption);

        var orderCode = selectedOption.getAttribute('data-order_code');
        var ongkir = selectedOption.getAttribute('data-ongkir');
        var expedisi = selectedOption.getAttribute('data-expedisi');
        var createdAt = selectedOption.getAttribute('data-created_at');

        document.getElementById('order_code').value = orderCode;
        document.getElementById('ongkir').value = ongkir;
        document.getElementById('expedisi').value = expedisi;
        document.getElementById('created_at').value = createdAt;

        // Show the fields
        document.getElementById('orderDetails').style.display = 'block';
        document.getElementById('ongkirDetails').style.display = 'block';
        document.getElementById('expedisiDetails').style.display = 'block';
        document.getElementById('created_atDetails').style.display = 'block';
    });
</script>

    <!-- /.container-fluid -->

<?= $this->include('layouts_admin/footer') ?>

           