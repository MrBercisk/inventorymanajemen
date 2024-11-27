
<?= $this->include('layouts_admin/header') ?>
<?= $this->include('layouts_admin/sidebar') ?>
 <!-- End of header sidebar navbar --> 

    <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
           
            <h1 class="h3 mb-4 text-gray-800">Management User</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" id="addUserBtn"><i class="fas fa-plus"></i> Tambah</button>
                            <div class="table-responsive">
                                <table id="userTable" class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Login</th>
                                            <th>Username</th>
                                            <th>Role</th>
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
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="addUserForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="login" class="form-label">Login</label>
                                <input type="text" class="form-control" id="login" name="login" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="idrole" class="form-label">Role</label>
                                <select class="form-control" id="idrole" name="idrole" required>
                                    <option value="" disabled selected>Pilih Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id']; ?>"><?= $role['role']; ?></option>
                                    <?php endforeach; ?>
                                </select>
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
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm">
                            <input type="hidden" name="id" id="editIdUsers">
                            <div class="form-group">
                                <label for="editNama">Username</label>
                                <input type="text" class="form-control" id="editusername" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="editLogin">Login</label>
                                <input type="text" class="form-control" id="editlogin" name="login" required>
                            </div>
                            <div class="form-group">
                                <label for="editNama">Password</label>
                                <input type="password" class="form-control" id="editpassword" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="idrole" class="form-label">Role</label>
                                <select class="form-control" id="editidrole" name="idrole" required>
                                    <option value="" disabled selected>Pilih Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id']; ?>"><?= $role['role']; ?></option>
                                    <?php endforeach; ?>
                                </select>
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

           