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
    <script src="<?=base_url('assets/js/custom.js')?>"></script>
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
    <?php 
        if(session()->get('id_app')==2){
            echo view('Kepegawaian/tBaseSidebar');
        }
        if(session()->get('id_app')==3){
            echo view('Presensi/tBaseSidebar');
        }
        if(session()->get('id_app')==4){
            echo view('Cuti/tBaseSidebar');
        }
        if(session()->get('id_app')==5){
            echo view('Persuratan/tBaseSidebar');
        }
    ?>

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

    <div class="modal" tabindex="-1" id="modal1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="modal1_header">
                    <!-- <h5 class="modal-title">Modal title</h5> -->
                    <button type="button" class="bg-danger btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal1_body">
                    <p>Modal body text goes here.</p>
                </div>
                <!-- <div class="modal-footer" id="modal1_footer" style="display: none;"> -->
                    <!-- <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" title="Close"><i class="fas fa-times"></i></button> -->
                <!-- </div> -->
            </div>
        </div>
    </div>


    <!-- SCRIPTS -->
    <script type="text/javascript">
        function showModalInfo(message, headers=1)
        {
            $('#modal1').modal('show')
            if(headers==0){
                $('#modal1_header').hide()
            }else{
                $('#modal1_header').show()
            }
            $('#modal1_body').html(message)
        }

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