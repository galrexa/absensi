<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=isset($title)?$title:'Page'?></title>
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/vendors/fontawesome/css/all.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/main.css')?>" rel="stylesheet">
    <script src="<?=base_url('assets/js/jquery-3.7.1.min.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers:{
                "Key": "<?=session()->get('key')?>",
                "User": '<?=session()->get('id')?>',
                "Token": '<?=session()->get('token')?>',
            }
        });
    </script>
    <style type="text/css">
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
            z-index: 1001;
            box-shadow: 0 -2px 20px rgba(0,0,0,0.1);
        }

        .nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #6c757d;
            padding: 8px 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 60px;
        }

        .nav-item.active {
            color: #3498db;
            background: rgba(52, 152, 219, 0.1);
        }

        .nav-item:hover {
            color: #3498db;
            background: rgba(52, 152, 219, 0.05);
            text-decoration: none;
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .nav-item span {
            font-size: 11px;
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header class="navbar navbar-expand-lg navbar-dark sticky-top header">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?=base_url('assets/img/FA-Logo-PCO_Horizontal-Emas-Putih.png')?>" alt="Brand Logo">
            </a>
            <img src="<?=session()->get('pegawai_foto')?>" height="50" class="rounded-circle">
        </div>
    </header>

    <!-- Shift Info -->
    <div class="shift-info">
        <div class="row mb-1">
            <div class="col">
                Hi <b><?=session()->get('nama')?></b>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?=$title?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid pt-2">
        <?php if(session()->get('message')){?>
            <!-- ALERT FLASHDATA -->
            <div class="alert alert-warning" id="flashdata_message">
                <i class="fa fa-exclamation-circle"></i> <?=session()->get('message')?>
            </div>
        <?php }?>
        <?php 
            // LOAD VIEW PAGE
            if(isset($page) && file_exists(APPPATH.'Views/'.$page.'.php'))
            {
                echo view($page);
            }else{
                echo '<h3 class="text-warning"><i class="fa fa-exclamation-circle"></i> Page not found</h3>';
            }
            // echo '<div class="m-4 alert alert-warning">'.json_encode(['APPPATH'=>APPPATH, 'WRITEPATH'=>WRITEPATH, 'FCPATH'=>FCPATH]).'</div>';
        ?>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-container">
            <div class="nav-item" onclick="window.location.assign('<?=base_url()?>')" title="Beranda" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </div>
            <?php if(return_access_link(['presensi/index'])){?>
                <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/index')?>')" title="Halaman Presensi" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <i class="fas fa-street-view"></i>
                    <span>Presensi</span>
                </div>
            <?php }?>
            <?php if(return_access_link(['presensi/riwayat'])){?>
                <div class="nav-item <?php if(check_link_module(['presensi/riwayat','presensi/laporan/kegiatan'])){echo 'active';}?>" onclick="window.location.assign('<?=site_url('presensi/riwayat')?>')" title="Riwayat Presensi" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <i class="fas fa-history"></i>
                    <span>Riwayat</span>
                </div>
            <?php }?>
            <?php if(return_access_link(['presensi/harian'])){?>
                <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/harian')?>')" title="Presensi Harian Pegawai" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <i class="fas fa-calendar-check"></i>
                    <span>Harian</span>
                </div>
            <?php }?>
            <?php if(return_access_link(['presensi/bulanan'])){?>
                <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/bulanan')?>')" title="Resume Presensi Bulanan" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Bulanan</span>
                </div>
            <?php }?>
        </div>
    </div>
    <!-- SCRIPTS -->
    <script>
        // aktifasi tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <!-- JAVASCRIPT -->
    <?=view('tBaseJavascript')?>
</body>
</html>