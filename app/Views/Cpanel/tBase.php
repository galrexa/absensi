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
</head>
<body>

    <!-- NAVBAR HEADER -->
    <?=view('tBaseHeader')?>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <label class="ml-1 mt-3 mb-1" style="font-weight: bold;">Menu</label>
            <?php if(return_access_link(['app'])){?>
                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('app')?>"><i class="fas fa-cubes"></i> Apps & Modul Sistem</a></li>
            <?php }?>
            <?php if(return_access_link(['data/konfigurasi','data/referensi','data/pengguna'])){?>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-dropdown" data-bs-toggle="collapse" href="#masterdataSubmenu" role="button" aria-expanded="false"><span><i class="fa fa-database"></i> Master Data</span><i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <div class="collapse submenu" id="masterdataSubmenu">
                        <ul class="sidebar-menu-sub">
                            <?php if(return_access_link(['data/konfigurasi'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('data/konfigurasi')?>">Parameter Konfigurasi</span></a></li>
                            <?php }?>
                            <?php if(return_access_link(['data/referensi'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('data/referensi')?>">Referensi</span></a></li>
                            <?php }?>
                            <?php if(return_access_link(['data/pengguna'])){?>
                                <li class="sidebar-item"><a class="sidebar-link" href="<?=site_url('data/pengguna')?>">Pengguna</span></a></li>
                            <?php }?>
                        </ul>
                    </div>
                </li>
            <?php }?>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
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
                echo '<h1 class="text-warning"><i class="fa fa-exclamation-circle"></i> Page not found</h1>';
            }
            echo '<div class="m-4 alert alert-warning">'.json_encode(['APPPATH'=>APPPATH, 'WRITEPATH'=>WRITEPATH, 'FCPATH'=>FCPATH]).'</div>';
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

        // // aktifasi tooltips
        // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        //     return new bootstrap.Tooltip(tooltipTriggerEl)
        // })
    </script>
    <!-- JAVASCRIPT -->
    <?=view('tBaseJavascript')?>
</body>
</html>