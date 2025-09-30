<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/vendors/fontawesome/css/all.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/main.css')?>" rel="stylesheet">
    <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <script src="<?=base_url('assets/js/jquery-3.7.1.min.js')?>"></script>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <?=view('tBaseHeader')?>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <!-- <div class="text-center pl-2 pr-2">
            <a href="<?=site_url('kepegawaian')?>" title="Edit Profile"><img src="/assets/img/logo_garuda.png" onerror="this.onerror=null; this.remove();" alt="1" height="120" class="rounded-circle"></a>
            <b class="d-block mt-2"><?=(session()->get('email'))?:'-'?></b>
        </div> -->
        <ul class="sidebar-menu">
            <label class="ml-1 mt-3 mb-1" style="font-weight: bold;">Menu</label>
            <?php #if(return_access_link(['kepegawaian'])){?>
                <!-- <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian')?>"><i class="fa fa-exclamation-circle"></i> Info</a></li> -->
            <?php #}?>
            <?php #if(return_access_link(['kepegawaian/profile'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/profile')?>"><i class="fas fa-user-circle"></i> Profile</a></li>
            <?php #}?>
            <?php #if(return_access_link(['kepegawaian/ulang_tahun'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/ulang_tahun')?>"><i class="fa fa-birthday-cake"></i> Ulang Tahun</a></li>
            <?php #}?>
            <li class="sidebar-item">
                <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#dashboardSubmenu" role="button" aria-expanded="false"><span><i class="fas fa-chart-pie"></i> Administrasi</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                <div class="collapse submenu" id="dashboardSubmenu">
                    <ul class="sidebar-menu-sub">
                        <?php #if(return_access_link(['kepegawaian/aktif'])){?>
                            <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/aktif')?>">Pegawai Aktif</a></li>
                            <?php /*foreach (session()->get('units') as $key) {?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/aktif?id='.$key->unit_kerja_id)?>">- <?=$key->unit_kerja_name?></a></li>
                            <?php }*/?>
                        <?php #}?>
                        <?php #if(return_access_link(['kepegawaian/non_aktif'])){?>
                            <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/non_aktif')?>">Pegawai Non Aktif (Arsip)</a></li>
                        <?php #}?>
                        <?php #if(return_access_link(['kepegawaian/tim'])){?>
                            <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/tim')?>">Tim Kerja</a></li>
                        <?php #}?>
                    </ul>
                </div>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#masterdataSubmenu" role="button" aria-expanded="false"><span><i class="fa fa-database"></i> Master Data</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                <div class="collapse submenu" id="masterdataSubmenu">
                    <ul class="sidebar-menu-sub">
                        <?php #if(return_access_link(['kepegawaian/unit'])){?>
                            <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/unit')?>">Unit Kerja</span></a></li>
                        <?php #}?>
                        <?php #if(return_access_link(['kepegawaian/jabatan'])){?>
                            <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/jabatan')?>">Jabatan</span></a></li>
                        <?php #}?>
                        <?php #if(return_access_link(['kepegawaian/gugus_tugas'])){?>
                            <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/gugus_tugas')?>">Gugus Tugas</span></a></li>
                        <?php #}?>
                        <?php #if(return_access_link(['kepegawaian/perguruan_tinggi'])){?>
                            <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('kepegawaian/perguruan_tinggi')?>">Perguruan Tinggi</span></a></li>
                        <?php #}?>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <?php if(session()->get('message')){?>
            <!-- ALERT FLASHDATA -->
            <div class="alert alert-info" id="flashdata_message">
                <i class="fa fa-exclamation-circle"></i> <?=session()->get('message')?>
            </div>
        <?php }?>
        <?php 
            // LOAD VIEW PAGE
            if(isset($page) && file_exists(APPPATH.'Views/'.$page.'.php'))
            {
                echo view($page);
            }else{
                echo '<h1 class="text-warning"><i class="fa fa-exclamation-circle"></i> Page not found</h1>';
            }
            // echo '<div class="m-4 alert alert-warning">'.json_encode(['APPPATH'=>APPPATH, 'WRITEPATH'=>WRITEPATH, 'FCPATH'=>FCPATH]).'</div>';
            // echo '<div class="alert alert-danger">'.json_encode($_SESSION).'</div>';
        ?>
    </div>

    <!-- SCRIPTS -->
    <script type="text/javascript">
        // Show dropdown on hover
        $('.dropdown').mouseover(function () {
            if($('.navbar-toggler').is(':hidden')) {
                $(this).addClass('show').attr('aria-expanded', 'true');
                $(this).find('.dropdown-menu').addClass('show');
            }
        }).mouseout(function () {
            if($('.navbar-toggler').is(':hidden')) {
                $(this).removeClass('show').attr('aria-expanded', 'false');
                $(this).find('.dropdown-menu').removeClass('show');
            }
        });

        // Go to the parent link on click
        $('.dropdown > a').click(function(){
            location.href = this.href;
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
            });
            const currentLocation = window.location.pathname;
            const queryString = window.location.search;
            const currentLink = currentLocation + queryString;
            const urlParams = new URLSearchParams(queryString);
            function setActiveMenu() {
                const navLinks = document.querySelectorAll('.sidebar .sidebar-link');
                navLinks.forEach(link => {
                    const linkPath = link.getAttribute('href');
                    link.classList.remove('active');
                    // console.log('CurrentLink::',currentLink,' href::',linkPath)
                    if (linkPath && linkPath !== '/' && currentLink.includes(linkPath.replace('<?=base_url()?>', ''))) {
                        link.classList.add('active');
                        console.log('CurrentLink::',currentLink,' href::',linkPath,' (ACTIVE)')
                        const parent = link.closest('.submenu');
                        if (parent) {
                            parent.classList.add('show');
                            const parentToggle = document.querySelector(`[href="#${parent.id}"]`);
                            if (parentToggle) {
                                parentToggle.setAttribute('aria-expanded', 'true');
                                parentToggle.classList.add('active');
                            }
                        }
                    }
                });
            }
            const dropdownToggles = document.querySelectorAll('.sidebar-dropdown');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = this.nextElementSibling;
                    const arrow = this.querySelector('.dropdown-arrow');
                    document.querySelectorAll('.submenu').forEach(function(item) {
                        if (item !== dropdown) {
                            item.classList.remove('show');
                            item.previousElementSibling.querySelector('.dropdown-arrow').classList.remove('active');
                            }
                    });
                    dropdown.classList.toggle('active');
                    arrow.classList.toggle('active');
                });
            });
            // Auto detect if this is the index page
            if (currentLink === '/' || currentLink.endsWith('index.php')) {
                const dashboardLink = document.querySelector('a[href="/index.php"]');
                if (dashboardLink) {
                    dashboardLink.classList.add('active');
                }
            } else {
                setActiveMenu();
            }
        })
    </script>
    <!-- JAVASCRIPT -->
    <?=view('tBaseJavascript')?>
</body>
</html>