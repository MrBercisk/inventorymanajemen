/* 
CREATED & MODIFIED BY
BIMS
PROGRAMMER
*/

$(document).ready(function () {
    $('#logorderTable').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: `LogOrder/get_datatable`,
            type: 'POST',
        },
        columns: [
            { data: 'id' },
            { data: 'order_code' },
            { data: 'nama_produk' },
            { data: 'catatan' },
            { data: 'ongkir' },
            { data: 'berat_gram' },
            { data: 'expedisi' },
            { data: 'status' },
            { data: 'created_at' },
            { data: 'updated_at' },
            { 
                data: null, 
                orderable: false, 
                render: function (data, type, row) {
                    if (row.catatan === 'Proses Pengiriman') {
                        return `<button class="btn btn-success btn-sm terima-barang" data-id="${row.id}">Selesai</button>`;
                    } else {
                        return '';
                    }
                }
            }
        ],
        createdRow: function (row, data) {
            // Apply background color based on status
            if (data.status === 'Confirmed') {
                $(row).css({
                    'background-color': 'lightgreen',
                    'color': 'white'
                });
            } else if (data.status === 'Rejected') {
                $(row).css({
                    'background-color': 'lightcoral',
                    'color': 'white'
                });
            }
        },
    });
    $('#logorderTable').on('click', '.terima-barang', function () {
        var id = $(this).data('id');
    
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menandai barang ini sebagai diterima?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `LogOrder/updateCatatan`,
                    type: 'POST',
                    data: {
                        id: id,
                        catatan: 'Barang Diterima'
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire(
                                'Berhasil!',
                                'Barang berhasil ditandai sebagai diterima.',
                                'success'
                            );
                            $('#logorderTable').DataTable().ajax.reload(); 
                        } else {
                            Swal.fire(
                                'Gagal!',
                                'Gagal memperbarui catatan. Silakan coba lagi.',
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Kesalahan!',
                            'Terjadi kesalahan pada server.',
                            'error'
                        );
                    }
                });
            }
        });
    });
    
    
    // Barang masuk 
    $('#detailKeluarTable').DataTable({
        responsive : false,
        autoWidth : false,
        processing : true,
        serverSide : true,
        ajax: {
            url: "BarangKeluar/get_datatable",
            type: "POST"
        },
        columns: [
            { data: 'iddetail' },
            { data: 'nama_produk' },
            { data: 'nama_supplier' },
            { data: 'qty' },
            { data: 'jumlah' },
            { data: 'total' },
            { data: 'keterangan' },
            { data: 'catatan' },
            { data: 'created_at' },
            { data: 'updated_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
       
              
    });
      // tombol klik tampil modal
      $('#addKeluarBtn').on('click', function () {
        $('#addKeluarModal').modal('show');
    });
     // untuk submit data
     $('#addKeluarForm').on('submit', function (e) {
        e.preventDefault();
    
        const formData = $(this).serialize(); 
    
        $.ajax({
            url: "BarangKeluar/create",
            method: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                }).then(() => {
                    $('#addKeluarModal').modal('hide'); 
                    $('#detailKeluarTable').DataTable().ajax.reload(); 
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
    $(document).on('click', '.delete-btn-keluar', function () {
        const id = $(this).data('id');
    
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "BarangKeluar/delete/" + id,
                    type: "DELETE",
                    success: function (response) {
                        Swal.fire('Dihapus!', response.message, 'success');
                        $('#detailKeluarTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });

    // edit data script
    // Ketika tombol Edit diklik
    $(document).on('click', '.edit-btn-keluar', function () {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'BarangKeluar/edit/' + id,
            method: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editIdKeluar').val(response.data.id);
                    $('#editidprodukkeluar').val(response.data.idprodukdetail);
                    $('#editidsupplierkeluar').val(response.data.idsupplier);
                    $('#editketerangankeluar').val(response.data.keterangan);
                    $('#editqtykeluar').val(response.data.qty);
                    $('#editjumlahkeluar').val(response.data.jumlah);
                    $('#editqtykeluar').val(response.data.qty);
                    $('#edittotalkeluar').val(response.data.total);
                    $('#editcatatankeluar').val(response.data.catatan);
                    $('#editKeluarModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat mengambil data');
            }
        });
    });

    $('#editKeluarForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize(); 

        $.ajax({
            url: 'BarangKeluar/update/' + $('#editIdKeluar').val(),
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        $('#editKeluarModal').modal('hide');
                        $('#detailKeluarTable').DataTable().ajax.reload();
                    });
                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data'
                });
            }
        });
    });

    
    $('#detailTable').DataTable({
        responsive : false,
        autoWidth : false,
        processing : true,
        serverSide : true,
        ajax: {
            url: "BarangMasuk/get_datatable",
            type: "POST"
        },
        columns: [
            { data: 'iddetail' },
            { data: 'nama_produk' },
            { data: 'nama_supplier' },
            { data: 'qty' },
            { data: 'jumlah' },
            { data: 'total' },
            { data: 'keterangan' },
            { data: 'catatan' },
            { data: 'created_at' },
            { data: 'updated_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
       
              
    });
     // tombol klik tampil modal
     $('#addMasukBtn').on('click', function () {
        $('#addMasukModal').modal('show');
    });
     // untuk submit data
     $('#addMasukForm').on('submit', function (e) {
        e.preventDefault();
    
        const formData = $(this).serialize(); 
    
        $.ajax({
            url: "BarangMasuk/create",
            method: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                }).then(() => {
                    $('#addMasukModal').modal('hide'); 
                    $('#detailTable').DataTable().ajax.reload(); 
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
    

    // untuk delete data
    $(document).on('click', '.delete-btn-masuk', function () {
        const id = $(this).data('id');
    
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "BarangMasuk/delete/" + id,
                    type: "DELETE",
                    success: function (response) {
                        Swal.fire('Dihapus!', response.message, 'success');
                        $('#detailTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });
     // edit data script
    // Ketika tombol Edit diklik
    $(document).on('click', '.edit-btn-masuk', function () {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'BarangMasuk/edit/' + id,
            method: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editIdMasuk').val(response.data.id);
                    $('#editidprodukdetail').val(response.data.idprodukdetail);
                    $('#editidsupplier').val(response.data.idsupplier);
                    $('#editketeranganmasuk').val(response.data.keterangan);
                    $('#editqtymasuk').val(response.data.qty);
                    $('#editjumlahmasuk').val(response.data.jumlah);
                    $('#editqtymasuk').val(response.data.qty);
                    $('#edittotalmasuk').val(response.data.total);
                    $('#editcatatanmasuk').val(response.data.catatan);
                    $('#editMasukModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat mengambil data');
            }
        });
    });

    $('#editMasukForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize(); 

        $.ajax({
            url: 'BarangMasuk/update/' + $('#editIdMasuk').val(),
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        $('#editMasukModal').modal('hide');
                        $('#detailTable').DataTable().ajax.reload();
                    });
                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data'
                });
            }
        });
    });


    $('#kategoriTable').DataTable({
        responsive : false,
        autoWidth : false,
        processing : true,
        serverSide : true,
        ajax: {
            url: "Kategori/get_datatable",
            type: "POST"
        },
        columns: [
            { data: 'id' },
            { data: 'nama' },
            { data: 'action', orderable: false, searchable: false }
        ]
       
              
    });
    // tombol klik tampil modal
      $('#addKategoriBtn').on('click', function () {
        $('#addKategoriModal').modal('show');
    });

    // untuk submit data
    $('#addKategoriForm').on('submit', function (e) {
        e.preventDefault();
    
        const formData = $(this).serialize(); 
    
        $.ajax({
            url: "Kategori/create",
            method: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                }).then(() => {
                    $('#addKategoriModal').modal('hide'); 
                    $('#kategoriTable').DataTable().ajax.reload(); 
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
    

    // untuk delete data
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
    
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "Kategori/delete/" + id,
                    type: "DELETE",
                    success: function (response) {
                        Swal.fire('Dihapus!', response.message, 'success');
                        $('#kategoriTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });

    // edit data script
    // Ketika tombol Edit diklik
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'Kategori/edit/' + id,
            method: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editId').val(response.data.id);
                    $('#editNama').val(response.data.nama);
                    $('#editKategoriModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat mengambil data');
            }
        });
    });

    $('#editKategoriForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize(); 

        $.ajax({
            url: 'Kategori/update/' + $('#editId').val(),
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        $('#editKategoriModal').modal('hide');
                        $('#kategoriTable').DataTable().ajax.reload();
                    });
                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data'
                });
            }
        });
    });


    // User manajemen
    $('#userTable').DataTable({
        responsive : false,
        autoWidth : false,
        processing : true,
        serverSide : true,
        ajax: {
            url: "UserManagement/get_datatable",
            type: "POST"
        },
        columns: [
            { data: 'id' },
            { data: 'login' },
            { data: 'username' },
            { data: 'role' },
            { data: 'action', orderable: false, searchable: false }
        ]
       
              
    });
    // tombol klik tampil modal
    $('#addUserBtn').on('click', function () {
        $('#addUserModal').modal('show');
    });

    $('#addUserForm').on('submit', function (e) {
        e.preventDefault();
    
        const formData = $(this).serialize(); 
    
        $.ajax({
            url: "UserManagement/create",
            method: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                }).then(() => {
                    $('#addUserModal').modal('hide'); 
                    $('#userTable').DataTable().ajax.reload(); 
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
     // untuk delete data
     $(document).on('click', '.delete-btn-users', function () {
        const id = $(this).data('id');
    
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "UserManagement/delete/" + id,
                    type: "DELETE",
                    success: function (response) {
                        Swal.fire('Dihapus!', response.message, 'success');
                        $('#userTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });

    // edit data script
    // Ketika tombol Edit diklik
    $(document).on('click', '.edit-btn-users', function () {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'UserManagement/edit/' + id,
            method: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editIdUsers').val(response.data.id);
                    $('#editusername').val(response.data.username);
                    $('#editlogin').val(response.data.login);
                    $('#editpassword').val(response.data.password);
                    $('#editidrole').val(response.data.idrole);
                    $('#editUserModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat mengambil data');
            }
        });
    });

    $('#editUserForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize(); 

        $.ajax({
            url: 'UserManagement/update/' + $('#editIdUsers').val(),
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        $('#editUserModal').modal('hide');
                        $('#userTable').DataTable().ajax.reload();
                    });
                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data'
                });
            }
        });
    });

    // ini menu stock
    $('#stockTable').DataTable({
        responsive : false,
        autoWidth : false,
        processing : true,
        serverSide : true,
        ajax: {
            url: "Stock/get_datatable",
            type: "POST"
        },
        columns: [
            { data: 'idproduk' },
            { data: 'nama_kategori' },
            { data: 'nama_produk' },
            { data: 'harga_beli' },
            { data: 'harga_jual' },
            { data: 'stok' },
            { data: 'keterangan' },
            { data: 'created_at' },
            { data: 'updated_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
       
              
    });
     // tombol klik tampil modal
     $('#addStockBtn').on('click', function () {
        $('#addStockModal').modal('show');
    });

    $('#addStockForm').on('submit', function (e) {
        e.preventDefault();
    
        const formData = $(this).serialize(); 
    
        $.ajax({
            url: "Stock/create",
            method: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                }).then(() => {
                    $('#addStockModal').modal('hide'); 
                    $('#stockTable').DataTable().ajax.reload(); 
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
    document.getElementById('harga_jual').addEventListener('input', function() {
        var hargaBeli = parseFloat(document.getElementById('harga_beli').value);
        var hargaJual = parseFloat(this.value);
        
        if (hargaBeli && hargaJual && hargaJual < hargaBeli) {
            document.getElementById('hargaWarning').classList.remove('d-none');
        } else {
            document.getElementById('hargaWarning').classList.add('d-none');
        }
    });
     // untuk delete data
     $(document).on('click', '.delete-btn-stock', function () {
        const id = $(this).data('id');
    
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "Stock/delete/" + id,
                    type: "DELETE",
                    success: function (response) {
                        Swal.fire('Dihapus!', response.message, 'success');
                        $('#stockTable').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal!', 'Gagal menghapus data.', 'error');
                    }
                });
            }
        });
    });

    // edit data script
    // Ketika tombol Edit diklik
    $(document).on('click', '.edit-btn-stock', function () {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'Stock/edit/' + id,
            method: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editIdStock').val(response.data.id);
                    $('#editnamaproduk').val(response.data.nama_produk);
                    $('#editidkategori').val(response.data.idkategori);
                    $('#editharga_beli').val(response.data.harga_beli);
                    $('#editharga_jual').val(response.data.harga_jual);
                    $('#editketerangan').val(response.data.keterangan);
                   
                    $('#editStockModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat mengambil data');
            }
        });
    });

    $('#editStockForm').on('submit', function (e) {
        e.preventDefault();

        var formData = $(this).serialize(); 

        $.ajax({
            url: 'Stock/update/' + $('#editIdStock').val(),
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        $('#editStockModal').modal('hide');
                        $('#stockTable').DataTable().ajax.reload();
                    });
                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data'
                });
            }
        });
    });

   
});