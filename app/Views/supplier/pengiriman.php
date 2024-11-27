<?= $this->include('layouts_supplier/header') ?>
<?= $this->include('layouts_supplier/sidebar') ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Proses Pengiriman Barang</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= base_url('Pengiriman/prosesPengiriman') ?>" method="post" id="proseskirim">
                <div class="form-group">
                    <label for="detailOrder">Pilih Detail Order</label>
                    <select class="form-control" id="detailOrder" name="iddetailorder" required>
                        <option value="" disabled selected>-- Pilih Detail Order --</option>
                        <?php foreach ($pengiriman as $item): ?>
                            <option value="<?= $item['idorder'] ?>">
                                Order ID: <?= $item['order_code'] ?> - <?= $item['pemesan'] ?> - Tgl order: <?= $item['tglorder'] ?> (<?= $item['alamat'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggalPengiriman">Tanggal Pengiriman</label>
                    <input type="date" class="form-control" id="tanggalPengiriman" name="tanggal_pengiriman" required>
                </div>
                <div class="form-group">
                    <label for="alamatPengiriman">Alamat Pengiriman</label>
                    <input type="text" class="form-control" id="alamatPengiriman" name="alamat_pengiriman" placeholder="Masukkan alamat pengiriman" required>
                </div>
                <div class="form-group">
                    <label for="provinceAsal">Provinsi Asal</label>
                    <select class="form-control" id="provinceAsal" name="province_asal" required>
                        <option value="" disabled selected>-- Pilih Provinsi Asal --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cityAsal">Kota/Kabupaten Asal</label>
                    <select class="form-control" id="cityAsal" name="kota_asal" required>
                        <option value="" disabled selected>-- Pilih Kota/Kabupaten Asal --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="provinceTujuan">Provinsi Tujuan</label>
                    <select class="form-control" id="provinceTujuan" name="province_tujuan" required>
                        <option value="" disabled selected>-- Pilih Provinsi Tujuan --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cityTujuan">Kota/Kabupaten Tujuan</label>
                    <select class="form-control" id="cityTujuan" name="kota_tujuan" required>
                        <option value="" disabled selected>-- Pilih Kota/Kabupaten Tujuan --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="weight">Berat (gram)</label>
                    <input type="number" class="form-control" id="weight" name="weight" placeholder="Masukkan berat barang dalam gram" required>
                </div>
                <div class="form-group">
                    <label for="courier">Kurir</label>
                    <select class="form-control" id="courier" name="courier" required>
                        <option value="" disabled selected>-- Pilih Expedisi --</option>
                        <option value="jne">JNE</option>
                        <option value="tiki">TIKI</option>
                        <option value="pos">POS Indonesia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ongkir">Ongkir</label>
                    <input type="text" class="form-control" id="ongkir" name="ongkir" placeholder="Ongkir akan dihitung otomatis" readonly>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>

<div id="loadingSpinner" style="display:none; text-align:center; margin-top: 20px;">
                <img src="<?= base_url('assets/images/load2.gif');?>" alt="Loading..." />
                 <p>Proses hitung ongkir...</p>
            </div>
        </div>
		 <style>
        #loadingSpinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            text-align: center;
            width: 200px;
        }
        #loadingSpinner img {
            width: 120px; 
            height: 120px; 
        }
        #loadingSpinner p {
            margin-top: 20px;
            font-size: 16px;
            color: black; 
        }
    </style>

<?= $this->include('layouts_supplier/footer') ?>
