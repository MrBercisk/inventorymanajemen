<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= $title; ?></title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon">

    <link href="assets/css/login/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/login/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/login/style.css" rel="stylesheet" type="text/css">
</head>

<body class="pb-0">

    <!-- Begin page -->
    <div class="accountbg"></div>

    <div class="wrapper-page account-page-full">

        <div class="card">
            <div class="card-body">

                <!-- <div class="text-center">
                    <a href="/login" class="logo"><img src="assets/img/logo.png" height="72" alt="logo"></a>
                </div> -->

                <div class="p-1">
                    <h1 class="text-center">Login</h1>
                    <h4 class="font-18 m-b-5 text-center text-primary">Selamat datang di Inventory Kantin</h4>
                    <p class="text-muted text-center">SD Muhammadiyah 3 Sidoarjo</p>
                    <?php if (session()->getFlashdata('success')): ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '<?= session()->getFlashdata('success') ?>',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        </script>
                    <?php elseif (session()->getFlashdata('error')): ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '<?= session()->getFlashdata('error') ?>',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        </script>
                    <?php endif; ?>
               

                    <form class="form-horizontal m-t-30" action="<?= base_url('/Auth/prosesLogin') ?>" method="POST">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Input Username anda...">
                        </div>

                        <div class="form-group">
                            <label for="userpassword">Password</label>
                            <input type="password" class="form-control" id="userpassword" name="password" placeholder="Input Password Anda ...">
                        </div>

                        <div class="form-group row m-t-20">
                            <div class="col-sm-12">
                                <button class="btn btn-success w-100 waves-effect waves-light" type="submit">Masuk</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>

        <div class="m-t-2 text-center">
            <p>2024 © BerlinBercisk</p>
        </div>

    </div>
    <!-- end wrapper-page -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>