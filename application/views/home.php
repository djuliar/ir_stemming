<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/css/scrolling-nav.css') ?>" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="#page-top">Information Retrieval App</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger active" href="<?= base_url() ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#publish">Upload</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" data-toggle="modal" data-target="#about-modal">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-info text-white">
        <div class="container text-center">
            <h1 class="font-weight-bold">Information Retrieval App</h1>
            <p class="lead">Aplikasi Information Retrieval pada Dokumen dengan Bahasa Indonesia</p>
        </div>
    </header>

    <section id="publish">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <h2>Upload Dokumen Anda</h2>
                    <p class="lead">Pilih file docx atau pdf dengan ukuran maksimal 2MB, kemudian tekan tombol Upload</p>
                    <form data-url="<?= base_url('home/upload') ?>" method="post" enctype="multipart/form-data" id="formUpload">
                    <div class="input-group input-group-lg mb-3">
                        <input type="file" name="dokumen" id="dokumen" class="form-control" placeholder="Masukkan File DOCX/PDF" accept=".docx, .pdf" >
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" id="upload"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                    </form>
                </div>
                <code class="col-md-12" id="hasil"></code>
                <?php if (isset($success) != ''){ ?>
                <div class="card">
                    <div class="card-body"><span><?= $success ?></span></div>
                </div>
            <?php } ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Tugas Teknologi Basis Data Lanjutan</p>
            <p class="m-0 text-center text-white font-weight-bold">UNIVERSITAS PENDIDIKAN GANESHA</p>
        </div>
        <!-- /.container -->
    </footer>

    <!-- Modal -->
    <div class="modal fade bg-dark" id="about-modal" tabindex="-1" role="dialog" aria-labelledby="about-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="about-modal-title">About</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="font-weight-bold">Information Retrieval App</h4>
                    <p class="lead">Aplikasi Information Retrieval pada Dokumen dengan Bahasa Indonesia</p>
                    <p class="lead">Created on December 2019</p>
                    <p class="lead">Team Member :</p>
                    <ol>
                        <li>David Juli Ariyadi (1829101044)</li>
                        <li>Ida Bagus Surya Dharma (1829101039)</li>
                        <li>Muhammad Saepuddin (1829101059)</li>
                    </ol>
                </div>
                <div class="modal-body text-center">
                    <h4 class="lead">Teknologi Basis Data Lanjutan</h4>
                    <p class="lead font-weight-bold">UNIVERSITAS PENDIDIKAN GANESHA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Plugin JavaScript -->
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom JavaScript for this theme -->
    <script src="<?= base_url('assets/js/scrolling-nav.js') ?>"></script>
    <script type="text/javascript">
        $('#formUpload').submit(function(e){
            e.preventDefault();
            $('#upload').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading');
            var url = $(this).data('url');
            var data = new FormData(this);
            $.ajax({
                url: url,
                data: data,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function(data){
                    $('#upload').html('<i class="fa fa-upload"></i> Upload');
                    $('#hasil').html(data);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    $('#upload').html('<i class="fa fa-upload"></i> Upload');
                    console.log(thrownError);
                }
            });
        });
    </script>
</body>
</html>