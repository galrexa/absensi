
  <header class="navbar navbar-expand-lg navbar-dark sticky-top header">
    <div class="container-fluid">
      <button class="navbar-toggler" id="sidebarToggle"><i class="fas fa-bars"></i></button>
      <a class="navbar-brand d-flex align-items-center" href="#">
        <!-- <i class="fa fa-code"></i> -->
        <!-- <?=APP_NAME?> -->
        <img src="<?=base_url('assets/img/FA-Logo-PCO_Horizontal-Emas-Putih.png')?>" alt="Brand Logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" aria-current="page" href="<?=base_url()?>">Home</a></li>
          <?php if(session()->get('login') && return_access_link(['app', 'ngemail'])){?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?=site_url('ngemail')?>"><i class="fa fa-envelope"></i> Ngemail</a></li>
                <?php if(return_access_link(['app'])){?>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="<?=site_url('app')?>"><i class="fa fa-wrench"></i> Konfigurasi Sistem</a></li>
                <?php }?>
              </ul>
            </li>
          <?php }?>
        </ul>
        <?php if(session()->get('login')){?>
          <!-- <form class="d-flex" role="search"> -->
            <!-- <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/> -->
            <!-- <button class="btn btn-outline-success" type="submit">Search</button> -->
          <!-- </form> -->
          <ul class="navbar-nav me-2 mb-2 mb-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- <i class="fa fa-user-circle"></i> -->
                <img src="<?=session()->get('pegawai_foto')?>" height="50" class="rounded-circle">
                <?=session()->get('email')?>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?=site_url('kepegawaian/profile')?>"><i class="fa fa-user-circle"></i> Profile</a></li>
                <li><a class="dropdown-item" href="<?=site_url('data/pengguna/form?id='.string_to(session()->get('id'),'encode'))?>"><i class="fa fa-key"></i> Ubah Password</a></li>
                <li><a class="dropdown-item" href="<?=site_url('auth/activate2fa')?>"><i class="fa fa-cog"></i> 2FA Setting</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" onclick="log_out()"><i class="fa fa-toggle-off"></i> Log Out</a></li>
              </ul>
            </li>
          </ul>
        <?php }?>
      </div>
    </div>
  </header>