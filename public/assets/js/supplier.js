$(document).ready(function () {
    $('#supplierOrderTable').DataTable({
        responsive: true, 
        autoWidth: false,  
        processing: true, 
        serverSide: true, 
        ajax: {
            url: "OrderSupplier/getOrders", 
            type: "POST", 
            data: function (d) {
                d.idsupplier = $('#idsupplier').val();
            }
        },
        columns: [
            { data: 'idorder', title: 'ID Order' },
            { data: 'pemesan', title: 'Pemesan' },
            { data: 'status', title: 'Status' },
            { data: 'catatan', title: 'Alasan(Jika reject order)' },
            { data: 'created_at', title: 'Tanggal Pesanan' },
            {
                data: 'action',
                title: 'Aksi',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm detail-order" data-id="${row.idorder}">Detail</button>
                        <button class="btn btn-success btn-sm confirm-order" data-id="${row.idorder}">Konfirmasi</button>
                        <button class="btn btn-danger btn-sm reject-order" data-id="${row.idorder}">Tolak</button>
                    `;
                }
            }
        ],
      
    });
    $('#supplierReturTable').DataTable({
        responsive: true, 
        autoWidth: false,  
        processing: true, 
        serverSide: true, 
        ajax: {
            url: "ReturSupplier/getOrders", 
            type: "POST", 
            data: function (d) {
                d.idsupplier = $('#idsupplier').val();
            }
        },
        columns: [
            { data: 'id' },
            { data: 'qty' },
            { data: 'alasan_pengembalian' },
            { data: 'tanggal_pengembalian' },
            { data: 'status' },
            {
                data: 'action',
                title: 'Aksi',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-success btn-sm confirm-retur" data-id="${row.id}">Konfirmasi</button>
                        <button class="btn btn-danger btn-sm reject-retur" data-id="${row.id}">Tolak</button>
                    `;
                }
            }
        ],
      
    });
    $('#supplierReturTable').on('click', '.confirm-retur', function () {
        const idRetur = $(this).data('id');

        Swal.fire({
            title: 'Konfirmasi Retur',
            text: 'Apakah Anda yakin ingin mengkonfirmasi retur ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Konfirmasi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ReturSupplier/confirmRetur',
                    type: 'POST',
                    data: { id: idRetur },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Retur Barang berhasil dikonfirmasi.',
                            icon: 'success'
                        });
                        $('#supplierReturTable').DataTable().ajax.reload();
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal mengkonfirmasi retur.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });



    // Event untuk tombol konfirmasi
    $('#supplierOrderTable').on('click', '.confirm-order', function () {
        const idOrder = $(this).data('id');

        Swal.fire({
            title: 'Konfirmasi Order',
            text: 'Apakah Anda yakin ingin mengkonfirmasi order ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Konfirmasi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'OrderSupplier/confirmOrder',
                    type: 'POST',
                    data: { idorder: idOrder },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Order berhasil dikonfirmasi.',
                            icon: 'success'
                        });
                        $('#supplierOrderTable').DataTable().ajax.reload();
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal mengkonfirmasi order.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Event untuk tombol tolak
    $('#supplierOrderTable').on('click', '.reject-order', function () {
        const idOrder = $(this).data('id');

        Swal.fire({
            title: 'Tolak Order',
            text: 'Apakah Anda yakin ingin menolak order ini?',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Masukkan alasan menolak order...',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal',
            preConfirm: (note) => {
                if (!note) {
                    Swal.showValidationMessage('Catatan tidak boleh kosong!');
                }
                return note;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const note = result.value;
                $.ajax({
                    url: 'OrderSupplier/rejectOrder',
                    type: 'POST',
                    data: { idorder: idOrder, catatan: note },
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Order berhasil ditolak.',
                            icon: 'success'
                        });
                        $('#supplierOrderTable').DataTable().ajax.reload();
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal menolak order.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
    $(document).on('click', '.detail-order', function() {
        var idorder = $(this).data('id');

        $.ajax({
            url: 'OrderSupplier/getOrderDetails',  
            method: 'POST',
            data: { idorder: idorder },
            success: function(response) {
                if (response.success) {
                    var details = response.data;
                    var list = $('#orderDetailsList');
                    list.empty(); 

                    details.forEach(function(detail) {
                        list.append(`
                            <li>
                                <strong>Kode Order:</strong> ${detail.order_code} <br>
                                <strong>Item:</strong> ${detail.nama_produk} <br>
                                <strong>Quantity:</strong> ${detail.qty} <br>
                                <strong>Harga: Rp.</strong> ${detail.harga} <br>
                                <strong>Total: Rp.</strong> ${detail.total} <br>
                                <strong>Tanggal Order:</strong> ${detail.created_at} <br>
                            </li>
                            <hr>
                        `);
                    });

                    $('#orderDetailsModal').modal('show');
                } else {
                    alert(response.message);  
                }
            },
            error: function() {
                alert('Failed to fetch order details.');
            }
        });
    });
    
    const BASE_URL = "http://localhost:8080/api";

    // Fetch Provinsi
    function fetchProvinces(selectElementId) {
        $.ajax({
            url: `${BASE_URL}/province`,
            method: "GET",
            success: function (response) {
                const provinces = response.rajaongkir.results;
                provinces.forEach(province => {
                    $(`#${selectElementId}`).append(
                        `<option value="${province.province_id}">${province.province}</option>`
                    );
                });
            },
            error: function (xhr) {
                console.error('Error fetching provinces:', xhr.responseJSON);
            }
        });
    }
    
    // Fetch Kota/Kabupaten
    function fetchCities(provinceId, selectElementId) {
        if (!provinceId) return;
    
        $.ajax({
            url: `${BASE_URL}/city`, 
            method: "GET",          
            data: { province: provinceId }, 
            success: function (response) {
                if (response.rajaongkir && response.rajaongkir.results) {
                    const cities = response.rajaongkir.results;
                           
                    $(`#${selectElementId}`).empty().append('<option value="" disabled selected>-- Pilih Kota/Kabupaten --</option>');
    
                    cities.forEach(city => {
                        $(`#${selectElementId}`).append(
                            `<option value="${city.city_id}" data-city-name="${city.city_name}">${city.city_name}</option>`
                        );
                    });
                } else {
                    console.error('No cities found for this province.');
                }
            },
            error: function (xhr) {
                console.error('Error fetching cities:', xhr.responseJSON);
            }
        });
    }
    

function fetchOngkir(origin, destination, weight = 1000, courier = "jne") {
    if (!origin || !destination) {
        console.error("Origin and destination must be selected");
        $('#ongkir').val('');
        return;
    }

    weight = $('#weight').val() || weight;  
    courier = $('#courier').val() || courier;  

    $.ajax({
        url: `${BASE_URL}/cost`,
        method: "POST",
        data: $.param({ 
            origin: origin,
            destination: destination,
            weight: weight,
            courier: courier
        }),
        success: function (response) {
            const costs = response.rajaongkir.results[0].costs[0].cost[0];
            $('#ongkir').val(costs.value);
        },
        error: function (xhr) {
            console.error('Error fetching ongkir:', xhr.responseJSON);
            $('#ongkir').val('');
        }
    });
}

function updateCityName(selectElementId, targetElementId) {
    const selectedOption = $(`#${selectElementId}`).find(':selected');
    const cityName = selectedOption.data('city-name');
    $(`#${targetElementId}`).text(cityName || '--');
}


fetchProvinces('provinceAsal');
fetchProvinces('provinceTujuan');

$('#provinceAsal').change(function () {
    const provinceId = $(this).val(); 
    if (provinceId) {
        fetchCities(provinceId, 'cityAsal'); 
    }
});

$('#provinceTujuan').change(function () {
    const provinceId = $(this).val(); 
    if (provinceId) {
        fetchCities(provinceId, 'cityTujuan'); 
    }
});

$('#cityAsal').change(function () {
    updateCityName('cityAsal', 'cityAsalName');
});

$('#cityTujuan').change(function () {
    updateCityName('cityTujuan', 'cityTujuanName');
    const origin = $('#cityAsal').val();
    const destination = $('#cityTujuan').val();
    const weight = $('#weight').val(); 
    const courier = $('#courier').val(); 
    if (origin && destination) {
        fetchOngkir(origin, destination, weight, courier);  
    }
});

// Event listener for weight change
$('#weight').change(function () {
    const origin = $('#cityAsal').val();
    const destination = $('#cityTujuan').val();
    const weight = $(this).val();
    const courier = $('#courier').val();
    if (origin && destination) {
        fetchOngkir(origin, destination, weight, courier);
    }
});

// Event listener for courier change
$('#courier').change(function () {
    const origin = $('#cityAsal').val();
    const destination = $('#cityTujuan').val();
    const weight = $('#weight').val();
    const courier = $(this).val();
    if (origin && destination) {
        fetchOngkir(origin, destination, weight, courier);
    }
});

    $('#proseskirim').on('submit', function (e) {
        e.preventDefault();
    
        const formData = $(this).serialize();
    
        $.ajax({
            url: "Pengiriman/prosesPengiriman",
            method: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                }).then(() => {
                    window.location.href = "Pengiriman"; 
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menambahkan data'
                });
            }
        });
    });
    

});
